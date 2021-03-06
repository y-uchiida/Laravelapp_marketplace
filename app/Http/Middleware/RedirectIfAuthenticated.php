<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// class RedirectIfAuthenticated
// {
//     /* 認証済みユーザーを、ダッシュボードなどログイン後のデフォルト画面へ遷移する */

//     private const GUARD_USER = 'users';
//     private const GUARD_OWNER = 'owners';
//     private const GUARD_ADMIN = 'admin';

//     /**
//      * Handle an incoming request.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @param  \Closure  $next
//      * @param  string|null  ...$guards
//      * @return mixed
//      */
//     public function handle(Request $request, Closure $next, ...$guards)
//     {
//         // $guards = empty($guards) ? [null] : $guards;

//         // foreach ($guards as $guard) {
//         //     if (Auth::guard($guard)->check()) {
//         //         return redirect(RouteServiceProvider::HOME);
//         //     }
//         // }

//         /* user 関連のURLだった場合の処理 */
//         if ($request->routeIs('user.*')){
//             if (Auth::guard(self::GUARD_USER)->check()){ /* ログイン済みなら、ユーザー機能のホームへ遷移する */
//                 return (redirect(RouteServiceProvider::HOME));
//             }
//         }

//         /* owner 関連のURLだった場合の処理 */
//         if ($request->routeIs('owner.*')){
//             if (Auth::guard(self::GUARD_OWNER)->check()){ /* ログイン済みなら、オーナー機能のホームへ遷移する */
//                 return (redirect(RouteServiceProvider::OWNER_HOME));
//             }
//         }

//         /* admin 関連のURLだった場合の処理 */
//         if ($request->routeIs('admin.*')){
//             if (Auth::guard(self::GUARD_ADMIN)->check()){ /* ログイン済みなら、管理者機能のホームへ遷移する */
//                 return (redirect(RouteServiceProvider::ADMIN_HOME));
//             }
//         }
//         return $next($request);
//     }
// }

class RedirectIfAuthenticated
{
    private const GUARD_USER = 'users';
    private const GUARD_OWNER = 'owners';
    private const GUARD_ADMIN = 'admin';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        // $guards = empty($guards) ? [null] : $guards;

        // foreach ($guards as $guard) {
        //     if (Auth::guard($guard)->check()) {
        //         return redirect(RouteServiceProvider::HOME);
        //     }
        // }

        if(Auth::guard(self::GUARD_USER)->check() && $request->routeIs('user.*')){
          return redirect(RouteServiceProvider::HOME);
        }

        if(Auth::guard(self::GUARD_OWNER)->check() && $request->routeIs('owner.*')){
          return redirect(RouteServiceProvider::OWNER_HOME);
        }

        if(Auth::guard(self::GUARD_ADMIN)->check() && $request->routeIs('admin.*')){
          return redirect(RouteServiceProvider::ADMIN_HOME);
        }

        return $next($request);
    }
}
