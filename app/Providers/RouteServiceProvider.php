<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    /* ホーム画面のURLを定数で指定 */
    public const HOME = '/';
    public const OWNER_HOME = '/owner/dashboard';
    public const ADMIN_HOME = '/admin/dashboard';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            /* api.php のルート情報への設定 */
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            /* web.php に記述しているルート情報の設定 */
            /* Route クラス(ファサード)のmiddleware() メソッドで、ミドルウェアを割り当てることができる
             * web ミドルウェアを、routes/web.php の中のすべてのルート設定に適用する
             */
            // Route::middleware('web')
            //     ->namespace($this->namespace)
            //     ->group(base_path('routes/web.php'));

            /* user 用の設定 */
            Route::prefix('/')
            ->as('user.') /* user. の名前を付けておき、ほかの認証種別と見分けをつけられるようにする */
            ->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));

            /* owner 用の設定 */
            Route::prefix('owner')
            ->as('owner.') /* owner. の名前を付けておき、ほかの認証種別と見分けをつけられるようにする */
            ->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/owner.php'));

            /* admin 用の設定 */
            Route::prefix('admin')
            ->as('admin.') /* admin. の名前を付けておき、ほかの認証種別と見分けをつけられるようにする */
            ->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/admin.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
