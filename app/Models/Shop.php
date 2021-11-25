<?php

namespace App\Models;

use App\Models\Owner;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/* Ownerモデルとのリレーションを設定するために読み込みしておく */
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    /* fillable 変数で、フォームからの入力内容で更新されるレコードを設定しておく */
    protected $fillable = [
        'owner_id',
        'name',
        'information',
        'filename',
        'is_selling',
    ];

    /* Owner モデルとの 1-1リレーションを記述(Shop belongs to a owner) */
    public function owner()
    {
        return ($this->belongsTo(Owner::class));
    }
}
