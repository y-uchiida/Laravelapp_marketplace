# HTTP例外の返却とカスタムエラーページ

## エラーページを返したい場合
`abort()` ヘルパ関数に、発生させたいHTTPエラーコードを渡すことで、任意の場所でHTTPエラーをレスポンスにすることができる  
`abort()` はHttp Exception を投げるので、例外処理されなければそのままエラーになる  

## デフォルトのエラーページをカスタマイズする
`resources/views/errors` に新しくblade ファイルを設置すればエラーページを独自実装できる  
デフォルトのページをベースにして変更を加えたい場合、artisan コマンドで  
デフォルトのページをresource ディレクトリ内に持ってくることができる

```
# resources/views/errors にエラーページのblade ファイルのデフォルトファイルが一式吐き出される
$ php artisan vendor:publish --tag=laravel-errors
Copied Directory [/vendor/laravel/framework/src/Illuminate/Foundation/Exceptions/views] To [/resources/views/errors]
Publishing complete.
```

## 参考
HTTP 例外 8.x Laravel:  
https://readouble.com/laravel/8.x/ja/errors.html#http-exceptions
