<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            1 => [
                ['designation' => 'Salade César', 'prix_ht' => 8.5, 'tva' => 10, 'stock' => 50, 'prix_variable' => 0, 'gerer_stock' => 1, 'facturation_poids' => 0],
                ['designation' => 'Soupe du jour', 'prix_ht' => 6.0, 'tva' => 10, 'stock' => 30, 'prix_variable' => 0, 'gerer_stock' => 1, 'facturation_poids' => 0]
            ],
            2 => [
                ['designation' => 'Steak Frites', 'prix_ht' => 18.0, 'tva' => 20, 'stock' => 40, 'prix_variable' => 0, 'gerer_stock' => 1, 'facturation_poids' => 0],
                ['designation' => 'Pâtes à la Carbonara', 'prix_ht' => 15.5, 'tva' => 20, 'stock' => 35, 'prix_variable' => 0, 'gerer_stock' => 1, 'facturation_poids' => 0]
            ]
        ];

        foreach ($articles as $sousFamilleId => $articleList) {
            foreach ($articleList as $article) {
                DB::table('articles')->insert(array_merge($article, [
                    'sous_famille_id' => $sousFamilleId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]));
            }
        }
    }
}
