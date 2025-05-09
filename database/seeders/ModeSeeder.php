<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ModeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modes = ['Espèces', 'Carte Bancaire', 'Chèque', 'Ticket Restaurant'];
        
        foreach ($modes as $mode) {
            DB::table('mode_paiements')->insert([
                'mode_paiement' => $mode,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
