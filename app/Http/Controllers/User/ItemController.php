<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function index()
    {
        /*
         * products の現在の在庫数を取得するクエリ
         * stocks テーブルのquantityの値を、product_idで合計すると、
         * 各商品の現在在庫がわかる
         * サブクエリとして実行するので、get() を付けずにクエリビルダの状態で保持しておく
         */
        $stocks = DB::table('t_stocks')
            ->select('product_id',
                DB::raw('sum(quantity) as quantity'))
            ->groupBy('product_id')
            ->having('quantity', '>', 1);

        /*
         * 販売可能な商品の一覧を取得するクエリ
         * Eloquent を使っていないので、そのままでは別テーブルの情報が結果に含まれない
         * join() メソッドを使って、必要なカラムを持つテーブルと連結する
         * joinSub() メソッドは、渡されたクエリを実行した結果をjoinするメソッド
         * name などは多くのテーブルで利用されているカラム名で、重複があるので、
         * どのテーブル化を特定できるように[テーブル名].[カラム名] の形で記述する
         * また、images は、それぞれimage1~image4 のカラム名を付けている
         */
        $products = DB::table('products')
            ->joinSub($stocks, 'stock', function ($join) {
                $join->on('products.id', '=', 'stock.product_id');
            })
            ->join('shops', 'products.shop_id', '=', 'shops.id')
            ->join('secondary_categories', 'products.secondary_category_id', '=', 'secondary_categories.id')
            ->join('images as image1', 'products.image1', '=', 'image1.id')
            ->join('images as image2', 'products.image2', '=', 'image2.id')
            ->join('images as image3', 'products.image3', '=', 'image3.id')
            ->join('images as image4', 'products.image4', '=', 'image4.id')
            ->where('shops.is_selling', true)
            ->where('products.is_selling', true)
            ->select('products.id', 'products.name as name', 'products.price'
                , 'products.sort_order as sort_order'
                , 'products.information', 'secondary_categories.name as category'
                , 'image1.filename as filename')
            ->get();

        return (view('user.index', compact('products')));
    }
}
