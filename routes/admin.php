<?php

/* Admin テーブル用の認証情報を設定するファイル
 * web.php と、そこからrequire されるauth.phpをベースに作成
 */

/* コントローラーへの参照を追加 */
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Admin\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Admin\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\Auth\VerifyEmailController;
use App\Http\Controllers\Admin\OwnersController;
use Illuminate\Support\Facades\Route;


/* --- 管理者画面ではwelcomeを利用しないので、コメントアウト --- */
// Route::get('/', function () {
//     return view('admin.welcome');
// });

/* admin ガードで認証するべきものは、すべて'admin'のプレフィックスをつけておく */

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth:admin'])->name('dashboard');

/* --- Owner モデルに対する操作(リソースコントローラ)へのルーティング --- */
/* --- Ownerの機能では、詳細表示の画面は利用しない(editだけで済ませる)ので、showアクションはexcept しておく --- */
Route::resource('owners', OwnersController::class)->middleware('auth:admin')->except(['show']);

/* --- 期限切れオーナー(ソフトデリート済みレコード)に対する操作 --- */
/* --- prefix() でルーティング設定に名前の接頭辞をつけて、ルーティング名設定の衝突を避ける --- */
Route::prefix('expired-owners')->middleware('auth:admin')->group(function () {
    Route::get('index', [OwnersController::class, 'expiredOwnerIndex'])->name('expired-owners.index');
    Route::delete('destroy/{owner}', [OwnersController::class, 'expiredOwnerDestroy'])->name('expired-owners.destroy');
});

/* --- 認証設定に関するルーティング --- */
Route::get('/register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');

Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
    ->middleware('auth:admin')
    ->name('verification.notice');

Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['auth:admin', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth:admin', 'throttle:6,1'])
    ->name('verification.send');

Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
    ->middleware('auth:admin')
    ->name('password.confirm');

Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
    ->middleware('auth:admin');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:admin')
    ->name('logout');
