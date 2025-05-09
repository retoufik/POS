<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $messages = [
            'Pas de sauce',
            'Sans sucre',
            'Sans gluten',
            'Extra fromage',
            'Légumes vapeur'
        ];
        
        foreach ($messages as $message) {
            DB::table('messages')->insert([
                'message' => $message,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
