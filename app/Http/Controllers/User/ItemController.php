<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Models\Product;
use App\Models\Stock;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:users');

        /* 販売中の商品かどうかを判定 */
        $this->middleware(function($request, $next){
            $item_id = $request->route()->parameter(item);

            if (!is_null(item_id)){
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

    public function index()
    {
        /* Products モデルから、注文可能な商品のみを取り出す(ローカルスコープavailableItems() を利用) */
        $products = Product::availableItems()->get();
        return (view('user.index', compact('products')));
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
