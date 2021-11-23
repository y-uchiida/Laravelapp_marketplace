# リソースコントローラ
特定のモデルに対して、CRUDの処理を定型的に処理するコントローラのこと
https://readouble.com/laravel/8.x/ja/controllers.html#resource-controllers

## artisan での雛形生成
```
# Dog モデルを扱うリソースコントローラの場合
# オプションとして '--resource' をつける
$ php artisan make:controller Controller --resource
```
CRUD処理のための7つのアクションを含めたコントローラが生成される

## ルーティング設定(web.php)
```
/* Dog リソースコントローラにルーティングする場合 */
Route::resource('dog', DogController::class);
```
artisanで生成した コントローラに対応する7つのアクションへのルーティングがまとめて行われる
