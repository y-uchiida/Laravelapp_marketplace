<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $owners_num = DB::table('owners')->count();
        $shops = [];

        /* $shops に、ownerのレコードの数だけインサートするデータを作る */
        for($i = 1; $i <= $owners_num; $i++){
            $shops[] = [
                'owner_id' => $i,
                'name' => "shop_name{$i}",
                'information' => 'infomation,infomation,infomation,infomation,infomation,infomation....',
                'filename' => '',
                'is_selling' => true
            ];
        }
        DB::table('shops')->insert($shops);
    }
}
