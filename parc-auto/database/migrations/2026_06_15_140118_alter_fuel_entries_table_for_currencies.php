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
        Schema::table('fuel_entries', function (Blueprint $table) {
            // 12 chiffres au total, 2 après la virgule pour le coût total
            $table->decimal('cout_total', 12, 2)->change();
            
            // 12 chiffres au total, 3 après la virgule pour le prix unitaire au litre
            $table->decimal('prix_unitaire', 12, 3)->change();
            
            // Sécurité bonus pour la quantité si de gros volumes sont enregistrés
            $table->decimal('quantite_litres', 10, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fuel_entries', function (Blueprint $table) {
            $table->decimal('cout_total', 10, 2)->change();
            $table->decimal('prix_unitaire', 6, 3)->change();
            $table->decimal('quantite_litres', 8, 2)->change();
        });
    }
};