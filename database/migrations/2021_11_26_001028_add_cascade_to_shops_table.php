<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCascadeToShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shops', function (Blueprint $table) {
            /* 既に設定してある外部キー制約をdropForeign() でいったん削除して、
             * ownersテーブルの連携レコードの更新・削除に連動して変更されるように設定を追加
             * $table->dropForeign('[テーブル名]_[外部キーを取り除くカラム名]_foreign');
             */
            $table->dropForeign('shops_owner_id_foreign');
            /* owners テーブルのidカラムと連結している(外部制約) */
            $table->foreign('owner_id')->references('id')->on('owners')
                ->onUpdate('cascade') /* ownersレコードの更新時に一緒に更新する */
                ->onDelete('cascade'); /* owners レコードの削除時に一緒に削除する */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shops', function (Blueprint $table) {
            //
        });
    }
}
