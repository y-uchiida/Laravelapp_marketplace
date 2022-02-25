<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            ['shop_id' => 1, 'name' => 'product_01', 'information' => 'product info', 'secondary_category_id' => 1, 'image1' => 1],
            ['shop_id' => 1, 'name' => 'product_02', 'information' => 'product info', 'secondary_category_id' => 2, 'image1' => 2],
            ['shop_id' => 1, 'name' => 'product_03', 'information' => 'product info', 'secondary_category_id' => 3, 'image1' => 3],
            ['shop_id' => 1, 'name' => 'product_04', 'information' => 'product info', 'secondary_category_id' => 4, 'image1' => 4],
            ['shop_id' => 1, 'name' => 'product_05', 'information' => 'product info', 'secondary_category_id' => 5, 'image1' => 1],
            ['shop_id' => 1, 'name' => 'product_06', 'information' => 'product info', 'secondary_category_id' => 6, 'image1' => 2],
            ['shop_id' => 1, 'name' => 'product_07', 'information' => 'product info', 'secondary_category_id' => 1, 'image1' => 3],
        ]);
    }
}
