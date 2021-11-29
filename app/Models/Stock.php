<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    /* Stockモデルで操作するテーブル名が`t_stocks` であり、
     * Laravelの命名規約外なので、明示的に設定する */
    protected $table = 't_stocks';
}
