# Stripe を利用して決済機能を実装する

## 概要
クレジット決済のAPIを提供しているサービス、Stripe  
テスト用機能として、クレジット決済が実行されないモードがあるので、練習にも利用できる  
Laravelの標準ライブラリであるcasher のほか、Stripeが提供しているライブラリ stripe/stripe-php もある  
前者は定期払い（サブスクリプション払い）に適しており、後者は比較的簡単に実装でき、都度払いが可能  

## 準備
1. Stripe アカウントを登録する  
   https://stripe.com/jp

2. ダッシュボードから、APIキーをコピーする  
   本番申請をするまでは、テスト環境用のAPIキーだけが利用できる  
   テスト環境用でも、漏洩には注意(Stripe サーバへの攻撃などに利用される恐れもあり)

3. .env ファイルに、APIキーを設定する  
   APIキーはStripeサーバでの認証を行う重要な情報なので、漏洩に注意  
   Laravel の.gitignore には.env を追跡しない設定になっているので、そのまま記述してOK  
   もしくは、Dockerの環境変数に設定して、.env で読み込みさせる、といった方法もある  

## パッケージの導入
```bash
$ composer require stripe/stripe-php
```

## 決済情報の送信
APIの使い方は、ドキュメントを参照  
https://stripe.com/docs/api

1. セッション情報を作成する  
   以下のページの内容をもとに、sessionを作成する
   https://stripe.com/docs/checkout/quickstart

```php
    /* CartController@checkout アクション */
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
            'success_url' => route('user.items.index'),
            'cancel_url' => route('user.cart.index'),
        ]);

        $publicKey = env('STRIPE_PUBLIC_KEY');

        return view('user.checkout', 
            compact('session', 'publicKey'));
```

2. カート内の数量が、データベースの実際の在庫数を超えていないことを確認  
   カード決済の処理は、StripeのAPIサーバからカード会社の決済処理まで複数のシステムが連携するため、完了するまで時間がかかる  
   決済処理が行われている間に在庫数が変動する可能性があり、在庫数不足に陥る問題が発生しうる    
   決済処理を行う前にデータベース内の在庫を減らしておき、決済後の欠品が生じないようにする

```php
    /* CartController@checkout アクション内の処理 */
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
```

3. session情報をブラウザにレスポンスし、view からStripeのカード情報入力画面にリダイレクトさせる  
   上記手順までで、自前で作っておく部分はほぼ完了  
   あとは、ブラウザにセッション情報をもたせた上でリダイレクトし、Stripeの画面へ遷移させる
   `checkout.blade.php` へアクセスされたらリダイレクトが発生するようにしておく

```html
<!-- chackout.blade.php -->
<p>決済ページへリダイレクトします。</p>
<script src="https://js.stripe.com/v3/"></script>
<script>
  const publicKey = '{{ $publicKey }}'
  const stripe = Stripe(publicKey)
  window.onload = function() {
    stripe.redirectToCheckout({
      sessionId: '{{ $session->id }}'
      }).then(function (result) {
        window.location.href = '{{ route('user.cart.index') }}';
        });
  }
</script>
```

3. 決済完了後、カート内の商品情報を削除する  
   決済ができたら、カートを辛煮する必要があるので、それを実装
   在庫数の減少処理は決済実施前に行っているので、carts テーブルからレコードを削除するだけでよい  
   

## 動作テスト
テスト環境では、以下のカード番号を利用できる  
これらの番号を入力して、決済処理をの動作を確認する

```
支払いが成功しました 4242 4242 4242 4242

支払いには認証が必要です 4000 0025 0000 3155

支払いが拒否されました 4000 0000 0000 9995
```
