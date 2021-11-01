# Laravelのサービスプロバイダ

## サービスプロバイダ = サービスコンテナにサービスをバインドするもの
サービスコンテナから便利に取り出すためには、まずサービスとバインドしないといけない
これを簡単にできるようにするのがサービスプロバイダ

## サービスプロバイダの作成
artisan コマンドが用意されている
```
$ php artisan make:provicer {サービスプロバイダ名}
// -> app/Providers/{サービスプロバイダ名}.php が生成される
```


## サービスプロバイダの構成
```
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SampleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        /* サービスをコンテナに追加する処理を書く */
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /* (公式より引用)
         * このメソッドは、他の全サービスプロバイダが登録し終えてから呼び出されます。
         * つまりフレームワークにより登録された、他のサービスすべてにアクセスできるのです。
         */
    }
}
```
サービスを追加する処理を`register()` に記述して、`app.php` にサービスプロバイダを登録しておくと、  
サービスコンテナに自動で追加されるので、ライフサイクル上のどこからでも呼び出して利用できるようになる！
