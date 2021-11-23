# Laravel のストレージ設定
`public` ディレクトリと、`storage` ディレクトリに設置することができる  

## public ディレクトリの用途
`public` ディレクトリはLravelのサービスとして固定的に利用するもの(jsやcssなど)を置く  

## storage ディレクトリの用途
`storage` ディレクトリは運用上、ユーザーがアップロードしたものなどを配置する  
`storage` ディレクトリ配下に置いたものは、gitignore で除外指定されているので、共通利用するリソースファイルはこちらに置かないこと

## storage ディレクトリへのシンボリックリンク指定
Larvelの初期設定では、`public` ディレクトリがインターネット経由でアクセスできるように設定されている  
storage ディレクトリへの参照は設定されていないため、シンボリックリンクを作成しないと利用できない
```
$ php artisan storage:link
```
このartisan コマンドで、`public` ディレクトリに `storage/app/public` への シンボリックリンクが作成され、  
インターネット経由で(URLを利用して)ファイルにアクセスができるようになる

参考: ファイルストレージ 8.x Laravel
https://readouble.com/laravel/8.x/ja/filesystem.html

## ファイル参照のためのヘルパ関数 asset()
`public` ディレクトリを頂点として、ファイルのURLを作ってくれるヘルパ関数
https://readouble.com/laravel/8.x/ja/helpers.html#method-asset

例えば `public/css/app.css` であれば、 ` asset('css/app.css') `になる

