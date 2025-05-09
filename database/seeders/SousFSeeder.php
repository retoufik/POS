<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class SousFSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            1 => ['Salades', 'Soupes'],
            2 => ['Viandes', 'Poissons', 'Pâtes'],
            3 => ['Glaces', 'Pâtisseries'],
            4 => ['Eaux', 'Sodas', 'Vins']
        ];

        foreach ($data as $familleId => $sousFamilles) {
            foreach ($sousFamilles as $sousFamille) {
                DB::table('sous_familles')->insert([
                    'sous_famille' => $sousFamille,
                    'famille_id' => $familleId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
