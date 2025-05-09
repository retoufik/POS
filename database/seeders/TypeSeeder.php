<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['Sur place', 'A emporter', 'Livraison'];
        
        foreach ($types as $type) {
            DB::table('types')->insert([
                'type' => $type,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
