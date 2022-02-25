<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            [
                'name' => 'admin001',
                'email' => 'admin001@example.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('admin001@example.com'),
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'admin_dummy000',
                'email' => 'admin_dummy000@example.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('admin_dummy000@example.com'),
                'created_at' => Carbon::now()
            ],
        ]);
    }
}
