<?php

namespace App\Models;

use App\Models\Image;
use App\Models\SecondaryCategory;
use App\Models\Shop;

/* リレーションさせるモデルを読み込み */
use App\Models\Stock;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'name',
        'information',
        'price',
        'is_selling',
        'sort_order',
        'secondary_category_id',
        'image1',
        'image2',
        'image3',
        'image4',
    ];

    public function shop()
    {
        return ($this->belongsTo(Shop::class));
    }

    public function category()
    {
        return ($this->belongsTo(SecondaryCategory::class, 'secondary_category_id'));
    }

    /* カラム名image1 とかぶってしまう名前は付けられないようなので、リレーション名はimageFirstにする */
    public function imageFirst()
    {
        /* 第1引数が連携先のモデルクラス、第2引数が外部キーカラム名、第3引数が連携先の主キーカラム名 */
        return ($this->belongsTo(Image::class, 'image1', 'id'));
    }

    public function imageSecond()
    {
        return ($this->belongsTo(Image::class, 'image2', 'id'));
    }
    public function imageThird()
    {
        return ($this->belongsTo(Image::class, 'image3', 'id'));
    }
    public function imageFourth()
    {
        return ($this->belongsTo(Image::class, 'image4', 'id'));
    }

    /* 在庫データモデル Stock とのリレーションを設定 */
    public function stock()
    {
        return ($this->hasMany(Stock::class));
    }

    /* 利用者データモデル Users とのリレーションを設定
     * 中間テーブル carts を通じて、各ユーザーがそれぞれの商品をいくつ購入しようとしているかを表現する
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'carts')
            ->withPivot(['id', 'quantity']);
    }

    /*  */
    public function scopeAvailableItems($query)
    {

        /* products の現在の在庫数を取得するクエリ
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
        return $query
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
            ->select(
                'products.id as id', 'products.name as name', 'products.price',
                'products.sort_order as sort_order', 'products.information',
                'secondary_categories.name as category', 'image1.filename as filename'
            );
    }

    /*
     * 商品一覧データの並び順を設定する
     * 第2引数 sortOrder は、利用する並び順を示す文字列を受け取る
     * 利用できる文字列は、Constants/common.php のSORT_ORDER 配列のvalue
     */
    public function scopeSortOrder($query, $sortOrder)
    {
        $order_type = \Constant::SORT_ORDER;

        if ($sortOrder === $order_type[\Constant::ORDER_RECOMMEND]['value']) {
            return $query->orderBy('sort_order', 'asc');
        } else if ($sortOrder === $order_type[\Constant::ORDER_HIGHER]['value']) {
            return $query->orderBy('price', 'desc');
        } else if ($sortOrder === $order_type[\Constant::ORDER_LOWER]['value']) {
            return $query->orderBy('price', 'asc');
        } else if ($sortOrder === $order_type[\Constant::ORDER_LATER]['value']) {
            return $query->orderBy('products.created_at', 'desc');
        } else if ($sortOrder === $order_type[\Constant::ORDER_OLDER]['value']) {
            return $query->orderBy('products.created_at', 'asc');
        }

        return $query->orderBy('sort_order', 'asc');
    }

    /*
     * 商品一覧から、選択したカテゴリIDの商品のみを絞り込みする
     */
    public function scopeSelectCategory($query, $categoryId)
    {
        if ($categoryId !== '0') {
            return $query->where('secondary_category_id', $categoryId);
        }
        return;
    }

    /*
     * 商品一覧から、指定したキーワードを含む標品のみを絞り込みする
     */
    public function scopeSearchKeyword($query, $keyword)
    {
        if (!is_null($keyword)) {
            $spaceConvert = mb_convert_kana($keyword, 's'); /* 全角スペースを半角に変換 */
            $keywords = preg_split('/[\s]+/', $spaceConvert, -1, PREG_SPLIT_NO_EMPTY); /* 文字列を半角スペースで配列に分割 */
            foreach ($keywords as $keyword) {
                $query->where('products.name', 'LIKE', "%$keyword%"); /* 部分一致をさせるために% %で検索キーワードを囲む */
            }
            return $query;
        } else {
            return ;
        }
    }
}
