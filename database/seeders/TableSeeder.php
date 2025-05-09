<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 7; $i++) {
            DB::table('tables')->insert([
                'Numero' => $i,
                'X' => rand(1, 10) * 100,
                'Y' => rand(1, 10) * 100,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
