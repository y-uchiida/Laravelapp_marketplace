<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/* リレーションさせるモデルを読み込み */
use App\Models\Shop;
use App\Models\SecondaryCategory;
use App\Models\Image;
use App\Models\Stock;

class Product extends Model
{
    use HasFactory;

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

    /* 在庫データモデル Stock とのリレーションを設定 */
    public function stock(){
        return ($this->hasMany(Stock::class));
    }
}
