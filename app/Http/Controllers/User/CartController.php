<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Stock;

/* carts テーブルにデータを保存するので、モデルを読み込み */
use App\Models\Cart;

/* ログインしているユーザーの情報を取得するため、Auth ファサードを読み込み */
use Illuminate\Support\Facades\Auth;


class CartController extends Controller
{
    public function index()
    {
        $user = User::findOrFail(Auth::id());
        $products = $user->products;
        $totalPrice = 0;

        /* ユーザーがカートに入れている商品の合計額を計算 */
        foreach($products as $product){
            $totalPrice += $product->price * $product->pivot->quantity;
        }

        return view('user.cart',
            compact('products', 'totalPrice'));
    }

    public function add(Request $request)
    {
        /* ログインしているユーザーのカート内に、選択した商品が入っているか */
        $itemInCart = Cart::where('product_id', $request->product_id)
            ->where('user_id', Auth::id())->first();

        if($itemInCart){
            /* 商品がすでに入っている場合、現在の数量に、選択した数量を追加する */
            $itemInCart->quantity += $request->quantity;
            $itemInCart->save();

        } else {
            /* 商品が入っていない場合、新しくレコードを追加する */
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        /* 商品登録後、カート画面へ遷移 */
        return redirect()->route('user.cart.index');
    }

    public function delete($id)
    {
        Cart::where('product_id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        return redirect()->route('user.cart.index');
    }

    /* Stripe のサービスを利用して、クレジットカードでの決済を行う */
    public function checkout()
    {
        $user = User::findOrFail(Auth::id());

        /* ログイン中のユーザーがカートに入れている商品のデータをもとに、
         * line_items の名称で決済内容のデータを作成する
         * フォーマットは、Stripe のAPIで決められているので、それに沿って設定する
         * https://stripe.com/docs/api/checkout/sessions/create?lang=php#create_checkout_session-line_items
         */
        $products = $user->products;
        $lineItems = [];
        foreach($products as $product){
            $quantity = '';
            /* データベースに記録されている入出庫記録を集計して、現在の在庫数を算出 */
            $quantity = Stock::where('product_id', $product->id)->sum('quantity');
            if($product->pivot->quantity > $quantity){
                /* カート内に入っている商品が、データベース上の在庫数よりも大きい場合、
                 * 決済処理を中断してカート画面に戻る
                 */
                return view('user.cart.index');
            } else {
                $lineItem = [
                    'name' => $product->name, /* 商品名 */
                    'description' => $product->information, /* 商品情報の詳細 */
                    'amount' => $product->price, /* 単価 */
                    'currency' => 'jpy', /* 決済通貨 */
                    'quantity' => $product->pivot->quantity, /* 数量 */
                ];
                /* 1商品を一つの連想配列にして、それを配列でまとめる */
                array_push($lineItems, $lineItem);
            }
        }

        /* Stripeで処理を行う前に、出庫データを作成しておく */
        foreach($products as $product){
            Stock::create([
                'product_id' => $product->id,
                'type' => \Constant::PRODUCT_LIST['reduce'],
                'quantity' => $product->pivot->quantity * -1
            ]);
        }

        /* シークレットキーを渡して、ライブラリを初期化 */
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        /* 作成した決済内容の配列を含めて、session情報を作成
         * フォーマットは下記URL参照
         * https://stripe.com/docs/api/checkout/sessions/create?lang=php
         */
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [$lineItems],
            'mode' => 'payment',
            'success_url' => route('user.cart.success'),
            'cancel_url' => route('user.cart.cancel'),
        ]);

        $publicKey = env('STRIPE_PUBLIC_KEY');

        return view('user.checkout', compact('session', 'publicKey'));
    }

    /* 決済処理が成功したら、カート内を空にする */
    public function success()
    {
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('user.items.index');
    }

    /* 決済画面から、戻るボタンで元の画面に戻ってきたとき場合の処理 */
    public function cancel()
    {
        $user = User::findOrFail(Auth::id());

        /* 在庫データから減少させた文の商品数量をもとに戻す */
        foreach($user->products as $product){
            Stock::create([
                'product_id' => $product->id,
                'type' => \Constant::PRODUCT_LIST['add'],
                'quantity' => $product->pivot->quantity
            ]);
        }

        return redirect()->route('user.cart.index');
    }
}
