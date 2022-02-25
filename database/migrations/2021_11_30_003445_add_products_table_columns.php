<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductsTableColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('name');
            $table->string('information')->nullable();
            $table->unsignedInteger('price')->default(100);
            $table->boolean('is_selling')->default(true);
            $table->integer('sort_order')->nullable();

            /* imageの利用数も増やす */
            $table->foreignId('image2')
                ->nullable()->constrained('images');
            $table->foreignId('image3')
                ->nullable()->constrained('images');
            $table->foreignId('image4')
                ->nullable()->constrained('images');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            /* カラムが存在する場合に削除を行う */
            if (Schema::hasColumn('products', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('products', 'information')) {
                $table->dropColumn('information');
            }
            if (Schema::hasColumn('products', 'price')) {
                $table->dropColumn('price');
            }
            if (Schema::hasColumn('products', 'is_selling')) {
                $table->dropColumn('is_selling');
            }
            if (Schema::hasColumn('products', 'sort_order')) {
                $table->dropColumn('sort_order');
            }


            /* 外部キー制約を持ったカラムを削除する場合は、まず外部キー制約を削除する必要がある */
            if (Schema::hasColumn('products', 'image2')) {
                $table->dropForeign('products_image2_foreign');
                $table->dropColumn('image2');
            }
            if (Schema::hasColumn('products', 'image3')) {
                $table->dropForeign('products_image3_foreign');
                $table->dropColumn('image3');
            }
            if (Schema::hasColumn('products', 'image4')) {
                $table->dropForeign('products_image4_foreign');
                $table->dropColumn('image4');
            }
        });
    }
}
