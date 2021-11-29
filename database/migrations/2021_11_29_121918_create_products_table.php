<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');

            /* Shop モデルとのリレーション用外部キー
             * shopテーブルのレコードと一緒に更新・削除する
             */
            $table->foreignId('shop_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            /* SecondaryCategory モデルとのリレーション用外部キー
             * カテゴリは変更・削除しない方針なので、連動は設定しない
             */
            $table->foreignId('secondary_category_id')
                ->constrained();

            /* Image モデル とのリレーション用外部キー
             * image とは独立して存在できるものなので、連動は設定しない
             * また、内容が入らない(画像を設定しない)場合があるので、nullable をつけておく
             */
            $table->foreignId('image1')
                ->nullable()
                ->constrained('images');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
