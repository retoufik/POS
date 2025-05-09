<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class EtatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $etats = [
            ['etat' => 'En cours', 'color' => '#FFA500'],
            ['etat' => 'Terminée', 'color' => '#00FF00'],
            ['etat' => 'Annulée', 'color' => '#FF0000']
        ];

        DB::table('etats')->insert($etats);
    }
}
