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
