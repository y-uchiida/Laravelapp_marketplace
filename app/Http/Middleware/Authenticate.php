<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Route;

class Authenticate extends Middleware
{
    protected $user_route = 'user.login';
    protected $owner_route = 'owner.login';
    protected $admin_route = 'admin.login';

    /* 未認証ユーザーが認証エリアへアクセスした際に、リダイレクトされるべきパスを取得する */
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // return route('login');

            /* RouteServiceProvider で設定したプリフィクスを使って、認証種別ごとに返り値を作る */
            if (Route::is('owner.*')) { /* owner 機能で未ログインの場合、ownerのログイン画面へ遷移する */
                return route($this->owner_route);
            } else if (Route::is('admin.*')) { /* admin 機能で未ログインの場合、adminのログイン画面へ遷移する */
                return route($this->admin_route);
            } else {
                return route($this->user_route); /* それ以外だった場合 */
            }
        }

    }
}
