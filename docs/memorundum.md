# develop memo

## ローカルでのサービス起動と停止
```
# 起動
$ ./vendor/bin/sail up -d

# 停止
sail stop
```

## env の APP_KEY
ハッシュの生成などに使われる  

> アプリケーションキー「APP_KEY」は、Illuminate暗号化サービスで使用され、32文字のランダムな文字列を設定する必要があります。  
> 正しく設定しないと、暗号化された文字列は安全ではありません。  
> アプリケーションキー「APP_KEY」は、セッションなどのすべての暗号化されたデータに使用されます。  

基本的には、Laravel アプリケーションの作成時に自動設定されるので、あまり気にしなくてよかったりする  
変更したい場合や、`git clone` したときに空になっている場合は以下コマンドで再設定
```
php artisan key:generate    
```
すでにハッシュ化されたものがある状態で再設定すると復号できなくなるので注意

## マイグレーションなど
myaql コンテナへアクセスする必要があるので、ホスト側から`php artisan` を実行しても動かない  
`sail` コマンドを使って、laravel.test コンテナから実行させる
```
# ホストで実行
$ sail artisan migrate
Nothing to migrate.
```

## 認証パッケージのインストール
```
# composer.json にbreezeを追加
$ composer require laravel/breeze "1.*" --dev

# インストール
$ php artisan breeze:install
Breeze scaffolding installed successfully.
Please execute the "npm install && npm run dev" command to build your assets.

# ビルド実行
$ npm install && npm run dev
# ... (中略) ...

   Laravel Mix v6.0.37


✔ Compiled Successfully in 4938ms
┌───────────────────────────────────────────────────────────────────────────────────────────────────────────┬──────────┐
│                                                                                                      File │ Size     │
├───────────────────────────────────────────────────────────────────────────────────────────────────────────┼──────────┤
│                                                                                                /js/app.js │ 681 KiB  │
│                                                                                               css/app.css │ 3.83 MiB │
└───────────────────────────────────────────────────────────────────────────────────────────────────────────┴──────────┘
webpack compiled successfully
```

## 多言語化(Localization)
1. 日本語変換ファイルをダウンロードして設置  
    https://github.com/Laravel-Lang/lang  
    `resources/lang/ja` に設置する  
2. 言語設定の変更
    `config/app.php` の `locale` を `ja` に変更  
		```
		locale = 'ja',
		```
3. ローカライズファイルの設定
    ダウンロードした内容のままではバリデーションメッセージに一部英語が残るので、これを修正  
    `resouces/lang/ja/validation.php` に `attributes` 配列を追加する  
		```
		'attributes' => ['name' => '氏名']
		```


## ルーティングの設定一覧
```
$ php artisan route:list
```