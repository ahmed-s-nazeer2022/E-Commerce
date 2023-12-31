<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Admin
        DB::table('users')->insert([
        //Admin
        [
            'name'=>'Admin',
            'email'=>'admin@gmail.com',
            'password'=>Hash::make('111'),
            'role'=>'admin',
            'status'=>'active',
        ],
        [//vendor
            'name'=>'Arian Vendor',
            'email'=>'vendor@gmail.com',
            'password'=>Hash::make('111'),
            'role'=>'vendor',
            'status'=>'active',
        ],
        [//user or customer
            'name'=>'Arian user',
            'email'=>'user@gmail.com',
            'password'=>Hash::make('111'),
            'role'=>'user',
            'status'=>'active',
        ]
        ]);

    }
}
