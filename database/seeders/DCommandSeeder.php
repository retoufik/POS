<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DCommandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articleIds = DB::table('articles')->pluck('id')->toArray();
        $commandeIds = DB::table('commandes')->pluck('id')->toArray();

        foreach ($commandeIds as $commandeId) {
            // Each order has 2-4 items
            $items = rand(2, 4);
            $selectedArticles = array_rand($articleIds, $items);
            
            if (!is_array($selectedArticles)) {
                $selectedArticles = [$selectedArticles];
            }

            foreach ($selectedArticles as $articleKey) {
                $articleId = $articleIds[$articleKey];
                $article = DB::table('articles')->find($articleId);
                
                DB::table('details_commandes')->insert([
                    'article_id' => $articleId,
                    'commande_id' => $commandeId,
                    'message_id' => rand(0, 1) ? rand(1, 5) : null,
                    'prix_ht' => $article->prix_ht,
                    'tva' => $article->tva,
                    'qte' => rand(1, 3),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
