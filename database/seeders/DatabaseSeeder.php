<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/* モデル経由でFactoryを利用するため読み込みしておく */
use App\Models\Product;
use App\Models\Stock;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            UsersTableSeeder::class,
            AdminsTableSeeder::class,
            OwnersTableSeeder::class,
            ShopsTableSeeder::class,
            ImagesTableSeeder::class,
            CategoriesTableSeeder::class,
            ProductsTableSeeder::class,
            StocksTableSeeder::class
        ]);

        /* ダミーデータの生成 */
        Product::factory(100)->create();
        Stock::factory(100)->create();
    }
}
