<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/* 各ユーザーのショッピングカート内の商品を表現する
 * users テーブルと products テーブルの関連性の中間テーブルになる
 * 外部キーだけではなく、それぞれのユーザーが商品をいくつカートに保存しているかを表す数値もテーブルに持つ
 */

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();

            /* users テーブルとの外部キー制約を設定 */
            $table->foreignId('user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            /* products テーブルとの外部キー制約を設定 */
            $table->foreignId('product_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            /* 購入予定の商品数(ユーザーがカートに入れている数) */
            $table->integer('quantity');

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
        Schema::dropIfExists('carts');
    }
}
