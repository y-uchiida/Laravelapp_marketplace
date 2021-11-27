<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/* ファイル保存用のファサード Storage を読み込み */
use Illuminate\Support\Facades\Storage;

/* アップロードされたファイルを編集(リサイズ)するため、InterventionImage を利用する */
use InterventionImage;

class ShopController extends Controller
{
    public function __construct()
    {
        /* owner としてログインできているかを判定 */
        $this->middleware('auth:owners');

        /* クロージャによるミドルウェア
         * URLのパスパラメータでshopのidを受け取っているので、
         * ログイン中のownerと関連のあるshopレコードのidかどうかを判定する
         * 別のowenerのidと関連しているshop id だった場合、表示せずに404ページを表示する
         */
        $this->middleware(function ($request, $next) {
            $id = $request->route()->parameter('shop'); // パスパラメータから、shopのid取得
            if (!is_null($id)) { // nullの場合(edit/{id} のURLではないとき)はshop idの判定をしない
                if (Shop::findOrFail($id)->owner->id !== Auth::id()) {
                    /* abort() でHttp Exception を発生させる */
                    abort(404);
                }
            }
            /* ミドルウェアなので、return $next($request) で処理をアクションメソッドに引き渡す
             */
            return $next($request);
        });
    }

    /* 店舗情報の表示 */
    public function index()
    {
        $shops = Shop::where('owner_id', Auth::id() )->get();
        return view('owner.shops.index', compact('shops'));
    }

    /* 店舗情報の編集画面 */
    public function edit($id)
    {
        $shop = Shop::findOrFail($id);
        return view('owner.shops.edit', compact('shop'));
    }

    /* 店舗情報の更新処理
     * ここではshopの画像データを受け取る
     */
    public function update(Request $request, $id)
    {
        $imageFile = $request->file('image');
        if($imageFile !== null && $imageFile->isValid() ){
            // Storage::putFile('public/shops', $imageFile); /* リサイズをせずに保存する場合の処 */

            /* InterventionImage を用いて画僧を 1920 * 1080 にリサイズする */
            $fileName = uniqid(rand().'_'); /* 1. ファイル名が一意な値になるように設定 */
            $extension = $imageFile->extension(); /* 拡張子を取得 */
            $fileNameToStore = $fileName. '.' . $extension;
            $resizedImage = InterventionImage::make($imageFile)->resize(1920, 1080)->encode();
            Storage::put('public/shops/' . $fileNameToStore, $resizedImage );
        }

        return redirect()->route('owner.shops.index');
    }

}
