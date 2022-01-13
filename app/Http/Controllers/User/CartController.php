<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\User;

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
}
