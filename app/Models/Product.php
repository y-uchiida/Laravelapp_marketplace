<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/* リレーションさせるモデルを読み込み */
use App\Models\Shop;
use App\Models\SecondaryCategory;
use App\Models\Image;
use App\Models\Stock;
use App\Models\User;

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
    public function stock(){
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
}
