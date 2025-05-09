<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('details_commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id');
            $table->foreignId('commande_id');
            $table->foreignId('message_id')->nullable();
            $table->double('prix_ht');
            $table->double('tva');
            $table->double('qte');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('details_commandes');
    }
};
