<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/* 各ユーザーのショッピングカート内の商品を表現する
 * users テーブルと products テーブルの関連性の中間テーブルになる
 * 外部キーだけではなく、それぞれのユーザーが商品をいくつカートに保存しているかを表す数値もテーブルに持つ
 */

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity'
    ];
}
