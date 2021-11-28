<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/* リレーション設定のためにOwenerモデルを追加 */
use App\Models\Owner;

class Image extends Model
{
    use HasFactory;

    /* Webフォームからの入力を受け付けるカラムを指定 */
    protected $fillable = [
        'owner_id',
        'filename'
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
}
