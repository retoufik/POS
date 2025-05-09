<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(
            [
                UserSeeder::class,
                FamilleSeeder::class,
                SousFSeeder::class,
                ArticleSeeder::class,
                EtatSeeder::class,
                TypeSeeder::class,
                ModeSeeder::class,
                TableSeeder::class,
                MessageSeeder::class,
                PaimentSeeder::class,
                DCommandSeeder::class
            ]
        );
    }
}
