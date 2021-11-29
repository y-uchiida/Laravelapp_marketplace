<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_stocks', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->tinyInteger('type'); /* 1: 入庫 / 2:出庫 */
            $table->integer('quantity'); /* 増減した在庫数 */

            /* products テーブルとの外部キー制約を設定
             * productsのレコードと一緒に変更・削除される */
            $table->foreignId('product_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');


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
        Schema::dropIfExists('t_stocks');
    }
}
