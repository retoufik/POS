<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class FamilleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $familles = ['Entrées', 'Plats', 'Desserts', 'Boissons'];
        
        foreach ($familles as $famille) {
            DB::table('familles')->insert([
                'famille' => $famille,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
