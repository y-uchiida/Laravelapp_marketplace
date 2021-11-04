<?php

use Illuminate\Support\Facades\Route;

/* コントローラーへの参照を追加 */
use App\Http\Controllers\BladeComponentSampleController;
use App\Http\Controllers\ServiceContainerTestController;
use App\Http\Controllers\ServiceProviderSampleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* users ガードで認証するべきものは、すべて'user'のプレフィックスをつけておく */

Route::get('/', function () {
    return view('user.welcome');
});

Route::get('/dashboard', function () {
    return view('user.dashboard');
})->middleware(['auth:users'])->name('dashboard');

/* Blade コンポーネントの動作テスト */
Route::get('/sample-component1', [BladeComponentSampleController::class, 'showSampleComponent1']);
Route::get('/sample-component2', [BladeComponentSampleController::class, 'showSampleComponent2']);

/* サービスコンテナの動作テスト */
Route::get('/show_servicecontainer', [ServiceContainerTestController::class, 'showServiceContainer']);
Route::get('/DI_test', [ServiceContainerTestController::class, 'DI_test']);

/* サービスプロバイダの動作テスト */
Route::get('ServiceProviderSample', [ServiceProviderSampleController::class, 'ServiceProviderSample']);
Route::get('add_serviceProviderSample', [ServiceProviderSampleController::class, 'add_serviceProviderSample']);

require __DIR__.'/auth.php';
