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

        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('heure');
            $table->foreignId('etat_id');
            $table->foreignId('type_id');
            $table->foreignId('table_id');
            $table->foreignId('user_id');
            $table->text('observation');
            $table->foreignId('mode_paiement_id');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
