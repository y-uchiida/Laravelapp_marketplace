<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'user_dummy000',
                'email' => 'user_dummy000@example.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('user_dummy000@example.com'),
                'created_at' => Carbon::now()
            ]
        ]);
    }
}
