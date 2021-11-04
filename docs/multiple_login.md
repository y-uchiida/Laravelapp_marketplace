# マルチログインの実装

## Authenticatable
Authenticatable を継承することで、Authに関する機能が利用できるようになる  
デフォルトで生成されるUserモデルはこれを継承している  
さらに、User モデルでは追加的な動作が設定されているので、それも利用する

## モデルの作成、マイグレーションの設定
`php artisan make:model {モデル名} -m`  
`-m` で、マイグレーションファイルも一緒に生成する  
マイグレーションファイル名は`create_{モデルに対応するテーブル名}_table` になる

マイグレーションファイルだけ作成する場合は、
`php artisan make:migration {マイグレーションファイル名}`

## パスワードリセット用のテーブルを作成
User モデルは、パスワードリセットのテーブルと持っているので、これも追加する  

## 認証用のルーティングの設定
require されたauth.php が丸ごと認証のためのルーティングを設定している  
これもUser モデルように設定されたものなので、OwnerとAdminで利用できるように変更する  
すべてweb.php に記述すると、肥大化してしまうので、`route/owner.php` と`rpute/admin.php` を作って、  
それぞれのルーティングの記述はそちらに分ける

## RouteServiceProvider
ルーティングに関する設定を取りまとめて行ってくれるサービスプロバイダ  
`app/Providers/RouteServiceProvider` にある  

まずは、各ユーザー種別ごとのhomeのURLを定数で設定  
また、認証種別ごとに読み込みするファイルを切り分けるため、ルーティング設定も変更する

## ガードの設定
Readouble より引用
> Laravelの認証機能は、基本的に「ガード」と「プロバイダ」で構成されています。ガードは、リクエストごとにユーザーを認証する方法を定義します。
> たとえば、Laravelには、セッションストレージとクッキーを使用して状態を維持する「セッション」ガードを用意しています。

> プロバイダは、永続ストレージからユーザーを取得する方法を定義します。LaravelはEloquentとデータベースクエリビルダを使用してユーザーを取得するためのサポートを用意しています。
> ただし、アプリケーションの必要性に応じて、追加のプロバイダを自由に定義できます。

`config/auth.php` の`guard`に、admin と ownerの設定を追加する


## ミドルウェアの設定
1. `app/Http/Middleware/Authenticate.php` で、未認証時にリダイレクトするURLを切り替える  
   `Route` ファサードのis() メソッドで、アクセスしたURLが引数で指定したパターンにマッチするかを判定できる  
   これを使って、認証が必要な領域かどうかをURLから判定する

2. `app/Http/Middleware/RedirectIfAuthenticated.php` で、認証済みユーザーの遷移先を設定する  
   `Auth` ファサードのguard() メソッドで、ガード対象(セッションガードで権限判定するユーザー)かどうかが分かる
   `$request->routeis()` でアクセスされたURLをがuser, owner, admin のどれかを含む場合、それに該当する認証種別でガード対象になっているかを調べる

## リクエストクラスの設定
`app/Http/Requests/Auth/LoginRequest.php` の中で、`authenticate()` メソッドがログイン試行時のリクエストを処理しているので、  
ここでアクセスされたURLごとに認証する対象のガード情報を切り替える  
(※ガード情報... auth.php のproviders で登録した、セッション情報とか関連するDBモデルとかを含めたもの)

## ルーティング設定の追加変更
owner.php, admin.php のそれぞれのミドルウェア設定を追加  
`middleware(['auth'])` となっている状態だとガードの設定がないので、  
`middleware(['auth:owners'])` のようにして、認証情報を確認する際の設定を追加  
`admin.php` の場合は、`middleware(['auth:admin'])` を設定

## コントローラの複製と変更
自動生成されたUser モデル用のコントローラ群が、`app/Http/Controllers/Admin` に入っている  
これをAdmin モデル用とOwner モデル用に設定変更する  
名前空間の変更、名前付きルーティングの変更、ガード設定の変更など。

## ビューの複製と変更
コントローラと同じく、自動生成されたUserモデル用のビューが`resources/views/admin` に入っている  
これを、Admin, Owner用にそれぞれ複製・変更する  
ログイン画面の条件設定や、ガード設定の変更など。  

さらに、画面全体のレイアウトを決めるビューファイル`views/layouts/navigation.blade.php` の中でも  
ログイン/ログアウトのURLが使われているので、これを認証種別ごとに振り分けておく必要がある  
`view/layouts/app.blade.php` から呼び出されているので、条件分岐を追加して、アクセスURLに応じて読み込みするファイルを切り替える
