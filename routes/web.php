<?php

use Illuminate\Support\Facades\Route;

/* コントローラーへの参照を追加 */
use App\Http\Controllers\BladeComponentSampleController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

/* Blade コンポーネントの動作テスト */
Route::get('/sample-component1', [BladeComponentSampleController::class, 'showSampleComponent1']);
Route::get('/sample-component2', [BladeComponentSampleController::class, 'showSampleComponent2']);

require __DIR__.'/auth.php';
