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
すべてweb.php に記述すると、肥大化してしまうので、`route/owner.php` と`route/admin.php` を作って、  
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

`guard` は、Laravelが標準で提供している認証機能の要素のひとつ  
ルーティングの際に、`middleware('auth')`で認証されたユーザーだけにアクセス許可ができるのがguradの機能  
設定を追加することで、`middleware('auth:認証設定名')` のように、設定を増やすことができる  
今回は新しい認証設定としてadmin とowner を追加し、認証を切り分ける  

参考記事: Laravel の Guard（認証） って実際何をやっているのじゃ？
https://qiita.com/tomoeine/items/40a966bf3801633cf90f

`config/auth.php` の`guard`に、admin と ownerの設定を追加する
1. 'guards' の中にuser, owners, admin を追加、設定内容はもともとあるwebの内容をベースに作る
   `provider` の項目をそれぞれの名前に変えておく
2. 'providers' に、user, owner, admin の項目を追加する
   モデルベースの認証に変更するので、`driver` を'eloquants', `model` をそれぞれのモデルに設定する
   もともと作られているuser の設定は取り除いてよい
3. 'passwords'の項目を設定する
   タイムアウト期限や認証の連続失敗回数を記述できる
   こちらも、もともとある記述をベースにuser, owner, admin それぞれの設定を作る


## ミドルウェアの設定
1. `app/Http/Middleware/Authenticate.php` で、未認証時にリダイレクトするURLを切り替える  
   `Route` ファサードのis() メソッドで、アクセスしたURLが引数で指定したパターンにマッチするかを判定できる  
   これを使って、認証が必要な領域かどうかをURLから判定する
   例えば、`Route::is('owner.*')` でowner の認証領域にアクセスしたかどうかを判定する  
   この記述ができるのは`app/Providers/RouteServiceProvider` の変更の際に、`as()` メソッドでルーティング情報に名前をつけてあるため  

2. `app/Http/Middleware/RedirectIfAuthenticated.php` で、認証済みユーザーの遷移先を設定する  
   ログイン済みのユーザーが再度ログイン画面にアクセスしてきたような場合に利用される  
   `Auth` ファサードのguard() メソッドで、ガード対象(セッションガードで権限判定するユーザー)かどうかが分かる
   `$request->routeis()` でアクセスされたURLがuser, owner, admin のどれかを含む場合、それに該当する認証種別でガード対象になっているかを調べる

## リクエストクラスの設定
`app/Http/Requests/Auth/LoginRequest.php` の中で、`authenticate()` メソッドがログイン試行時のリクエストを処理しているので、  
ここでアクセスされたURLごとに認証する対象のガード情報を切り替える  
(※ガード情報... auth.php のproviders で登録した、セッション情報とか関連するDBモデルとかを含めたもの)  
ここの設定を作っておくことで、admin のログインURLでログイン試行した場合にはadmin テーブルの内容を参照してログイン判定をしてくれる  
owners の場合も同様に設定を作っておく

## ルーティング設定の追加変更
owner.php, admin.php のそれぞれのミドルウェア設定を追加  
`middleware(['auth'])` となっている状態だとガードの設定がない(デフォルトのusers テーブルでの認証を試行してしまう)ので、  
`middleware(['auth:owners'])` のようにして、認証情報のガード設定を追加して、参照するテーブルを振り分ける  
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
