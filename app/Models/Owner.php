<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes; /* 論理削除の動作を利用 */
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/* 認証機能を使うため、親クラスはModelではなくAuthenticatableにする */
// class Owner extends Model
class Owner extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes; /* class内でもSoftDeletes を記述しておく */

    /* fillable: 以下のカラムを、外部から受け取ったデータで上書き可能にする */
    /**
    * The attributes that are mass assignable.
    *
    * @var string[]
    */
   protected $fillable = [
       'name',
       'email',
       'password',
   ];

   /* hidden: シリアル化のために非表示にする */
   /**
    * The attributes that should be hidden for serialization.
    *
    * @var array
    */
   protected $hidden = [
       'password',
       'remember_token',
   ];

   /* casts: 指定のデータ型にキャストしてから保存する
    * ここでは日付時刻を受け取るカラムを datetime 型に変換するように設定している
    */
   /**
    * The attributes that should be cast.
    *
    * @var array
    */
   protected $casts = [
       'email_verified_at' => 'datetime',
   ];
}
