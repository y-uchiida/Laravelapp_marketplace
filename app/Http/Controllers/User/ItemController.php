<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Stock;
use App\Models\PrimaryCategory; /* 検索絞り込みのスコープ用に読み込み */

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:users');

        /* 販売中の商品かどうかを判定 */
        $this->middleware(function($request, $next){
            $item_id = $request->route()->parameter('item');

            if (!is_null($item_id)){
                /* availableItems() で注文可能な商品を絞り込み、その中にパスパラメータで指定された商品IDがあるか判定 */
                $is_availableItem = Product::availableItems()->where('products.id', $item_id)->exists();
                if (!$is_availableItem){
                    /* 販売中の商品ではない場合、404 エラーを返す */
                    abort(404);
                }
            }
            /* 販売中の商品の場合、次のミドルウェアへ処理を渡す */
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        /* 検索フォームで選択されたカテゴリに絞り込みするため、カテゴリ一覧を取得 */
        $categories = PrimaryCategory::with('secondary')->get();

        $products = Product::availableItems() /* Products モデルから、注文可能な商品のみを取り出す(ローカルスコープavailableItems() を利用) */
            ->selectCategory($request->category ?? '0') /* 選択したカテゴリの商品のみを取り出す(ローカルスコープselectCategory() を利用) */
            ->searchKeyword($request->keyword) /* 入力シアキーワードに合致する名称の商品のみを取り出す(ローカルスコープsearchKeyword() を利用) */
            ->sortOrder($request->sort)
            ->paginate($request->pagination ?? '20'); /* ページングを実装するため、get() ではなく paginate() を利用 (件数指定がない場合は、null合体演算子を用いて20にする) */
        return (view('user.index', compact('products', 'categories')));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        /* 現在の在庫数をstocks テーブルの該当レコードの合計値から取得する */
        $quantity = Stock::where('product_id', $product->id)->sum('quantity');

        if ($quantity > 9){
            $quantity = 9;
        }
        return (view('user.show', compact('product', 'quantity')));
    }

}
