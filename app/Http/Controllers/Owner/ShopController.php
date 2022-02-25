<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Services\ImageService;

/* バリデーションロジックを切り離すため、UploadImageRequest を読み込み */
use App\Http\Requests\UploadImageRequest;

/* 共通処理として分離したアップロード処理を含むサービスクラスを読み込み */
use Illuminate\Support\Facades\Auth;

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
        $shops = Shop::where('owner_id', Auth::id())->get();
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
    public function update(UploadImageRequest $request, $id)
    {
        /* shops テーブルのカラムに対するバリデーション */
        $request->validate([
            'name' => 'required|string|max:50',
            'information' => 'required|string|max:1000',
            'is_selling' => 'required',
        ]);

        /* 店舗画像の保存処理 */
        $imageFile = $request->file('image');
        if ($imageFile !== null && $imageFile->isValid()) {
            // Storage::putFile('public/shops', $imageFile); /* リサイズをせずに保存する場合の処理 */

            $fileNameToStore = ImageService::upload($imageFile, 'shops');
        }

        /* 店舗情報の保存処理 */
        $shop = Shop::findOrFail($id);
        $shop->name = $request->name;
        $shop->information = $request->information;
        $shop->is_selling = $request->is_selling;
        if (!is_null($imageFile) && $imageFile->isValid()) {
            $shop->filename = $fileNameToStore;
        }

        $shop->save();

        return redirect()
            ->route('owner.shops.index')
            ->with(['message' => '店舗情報を更新しました。', 'status' => 'info']);
    }
}
