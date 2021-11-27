# 画像のアップロード

## サンプル画像を探すときのおすすめ
- pixabay
- 

## ブラウザからサーバへの画像送信
ブラウザのフォームからの画像を送信は、通常の手順で行う  
- formをPostメソッドにする
- 画像を受け取るため、`enctype=multipart/form-data` をform タグに設定する
- 画像受け取り、`type=file` のinputタグを設置してファイルを選択させる 

## Larvel側での受け取りと保存
フォームからの通信を受け取ったアクションメソッドで`Request` オブジェクトを利用して送信内容を操作できる  
name 属性を`uploadImage` に設定したinputで画像をアップロードしていた場合、
`$request->uploadImage` にアップロードされた画像のオブジェクトが保持されている  
このほかにも、`$request->file('uploadImage')`でも、アップロードされた画像が取得できる  
Request オブジェクトは`Illuminate\Http\UploadedFile::isValid()` でアップロードの正常性を確認できるので、これを使ってエラーチェックした後にファイルを保存する
サーバのディスクへの保存は、`Storage` ファサードのメソッドが利用できる  
```
/* uploadImage というファイルがフォームからアップロードされていたら保存する
 * 保存先は、storage/app/public/ランダム文字列
 */

if ($request->hasfile('uploadImage') && $request->file('uploadImage')->isValid())
{
    Storage::putFile('public', $request->file('uploadImage'));
}
```

### アップロードされたファイルを操作するメソッド・プロパティ
| $request->file('フォームのname属性値') | 

| メソッド名 | 返り値 | name属性値がuploadImageの場合の記述例 | 備考 |
| --- | --- | --- | --- |
| $request->フォームのname属性値 | Illuminate\Http\UploadedFile オブジェクト | $request->uploadFile | file形式ではない場合も取得できてしまう(uploadedFileオブジェクトとは限らない) |
| $request->file('フォームのname属性値') | Illuminate\Http\UploadedFile オブジェクト | $request->file('uploadImage') | 指定されたname属性値がない場合、またはtype="file" で送信されていない場合(type="text"だった場合など)はnullになる |
| $request->hasFile('フォームのname属性値') | bool | $request->hasFile('uploadImage') | フォームから、指定のname属性でファイルが送信されている場合はtrue |
| Illuminate\Http\UploadedFile::isValid() | bool | $request->file('uploadfile')->isValid() | ファイルがアップロードされ、HTTPエラーが発生していなければtrue<br>uploadedFileオブジェクトのメソッドなので、$request->file() などで取得した際にnullになっていないかには注意が必要 |
| Storage::putFile(格納先パス, UploadedFileオブジェクト) | string/false | Storage::putFile('path/to/dir', $request->file('uploadImage')) | ファイルを任意の場所に保存する<br>ファイル名は一意な文字列が自動設定される |
| Storage::putFileAs(格納先パス, UploadedFileオブジェクト, 保存ファイル名) | string/false | Storage::putFile('path/to/dir', $request->file('uploadImage', ファイル名)) | ファイルを任意の場所とファイル名で保存する |


## アップロードされた画像をサーバー側でリサイズする
Intervention Image を利用する
1. composer でインストール
```
composer require intervention/image
```

2. InterventionImage をprovider に追加
app\config\app.php を編集
```
    'providers' => [
    ...
        /*
         * Package Service Providers...
         */
        Intervention\Image\ImageServiceProvider::class,
    ],

    /* 中略 */
    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [
        /* ... */
        /* アップロードされたファイルを編集するためのInterventionImageへのエイリアスを追加 */
        'Image' => Intervention\Image\Facades\Image::class,
    ],
```


## 参考
- 正常なアップロードのバリデーション 8.x Laravel  
  https://readouble.com/laravel/8.x/ja/requests.html#validating-successful-uploads

