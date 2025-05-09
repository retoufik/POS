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

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('designation');
            $table->double('prix_ht');
            $table->double('tva');
            $table->double('stock');
            $table->boolean('prix_variable');
            $table->boolean('gerer_stock');
            $table->boolean('facturation_poids');
            $table->foreignId('sous_famille_id');
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
