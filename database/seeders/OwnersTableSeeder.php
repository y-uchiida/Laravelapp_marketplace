<?php

namespace Database\Seeders;


use Carbon\Carbon;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OwnersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('owners')->insert([
            [
                'name' => 'owner_dummy001',
                'email' => 'owner_dummy001@example.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('owner_dummy001@example.com'),
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'owner_dummy002',
                'email' => 'owner_dummy002@example.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('owner_dummy002@example.com'),
                'created_at' => Carbon::now()
            ],
        ]);
    }
}
