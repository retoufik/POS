<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class PaimentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            DB::table('commandes')->insert([
                'date' => now()->subDays(rand(0, 7))->toDateString(),
                'heure' => now()->subHours(rand(1, 24))->toTimeString(),
                'etat_id' => rand(1, 3),
                'type_id' => rand(1, 3),
                'table_id' => rand(1, 10),
                'user_id' => 2,
                'observation' => 'Observation pour commande #' . $i,
                'mode_paiement_id' => rand(1, 4),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
