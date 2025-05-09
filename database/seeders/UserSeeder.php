<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'login' => 'admin',
                'password' => bcrypt('password'),
                'email' => 'admin@example.com',
                'isAdmin' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Waiter User',
                'login' => 'waiter',
                'password' => bcrypt('password'),
                'email' => 'waiter@example.com',
                'isAdmin' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
