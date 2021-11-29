<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('images')->insert([
            ['owner_id' => 1, 'filename' => 'product_image_sample01.jpg', 'title' => null],
            ['owner_id' => 1, 'filename' => 'product_image_sample02.jpg', 'title' => null],
            ['owner_id' => 1, 'filename' => 'product_image_sample03.jpg', 'title' => null],
            ['owner_id' => 1, 'filename' => 'product_image_sample04.jpg', 'title' => null],
        ]);
    }
}
