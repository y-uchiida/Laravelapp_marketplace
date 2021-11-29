<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* primary_category の登録 */
        DB::table('primary_categories')->insert([
            [ 'name' => '家電・TV・カメラ', 'sort_order' => 1 ],
            [ 'name' => '本・電子書籍・音楽', 'sort_order' => 2 ],
            [ 'name' => '日用雑貨・キッチン用品', 'sort_order' => 3 ],
        ]);

        /* secondary_category の登録 */
        DB::table('secondary_categories')->insert([
            [ 'name' => '家電', 'sort_order' => 1, 'primary_category_id' => 1 ],
            [ 'name' => 'テレビ・オーディオ・カメラ', 'sort_order' => 2, 'primary_category_id' => 1 ],
            [ 'name' => '本', 'sort_order' => 3, 'primary_category_id' => 2 ],
            [ 'name' => 'DVD・CD', 'sort_order' => 4, 'primary_category_id' => 2 ],
            [ 'name' => '日用品雑貨・文房具・手芸', 'sort_order' => 5, 'primary_category_id' => 3 ],
            [ 'name' => 'キッチン用品・食器・調理器具', 'sort_order' => 6, 'primary_category_id' => 3 ],
        ]);
    }
}
