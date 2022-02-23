<?php

/* CartService.php
 * ショッピングカート関連の処理を記述する
 */

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;

class CartService
{
    /* getItemsInCart()
     * 現在のカート内に保存されている商品の一覧を取得する
     * 購入完了メールを送る際に必要なデータ（オーナー名など）を一つの配列に直している
     * パラメータは、カート内の商品情報を受け取る
     */
    public static function getItemsInCart($items)
    {
        $products = [];

        foreach ($items as $item) { /* パラメータで渡された商品一覧に対してループ */
            $p = Product::findOrFail($item->product_id);

            /* オーナー情報を取得する際、 name カラムが商品テーブルと重複するので、
             * ownerName に変更して保持する
             */
            $owner = $p->shop->owner->select('name', 'email')->first()->toArray();
            $values = array_values($owner);
            $keys = ['ownerName', 'email'];
            $ownerInfo = array_combine($keys, $values);

            /* 商品情報を取得する */
            $product = Product::where('id', $item->product_id)
                ->select('id', 'name', 'price')->get()->toArray();

            /* 購入数を取得する */
            $quantity = Cart::where('product_id', $item->product_id)
                ->select('quantity')->get()->toArray();

            /* product と quantity は toArray() で配列化しており、データの入っている階層が異なる
             * id 一致で取得しており、一つしかないのは確定なので、[0]をつけて絞っておく
             * array_merge() で一つの配列に結合する
             */
            $result = array_merge($product[0], $ownerInfo, $quantity[0]);

            /* 結合した商品データを、返り値用の配列に追加 */
            array_push($products, $result);
        }
        dd($products);
        return $products;

    }
}
