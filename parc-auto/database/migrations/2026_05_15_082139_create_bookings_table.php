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
    Schema::create('bookings', function (Blueprint $table) {
        $table->id();
        
        // Relations
        // On lie au Driver (le chauffeur physique)
        $table->foreignId('driver_id')->constrained()->onDelete('cascade'); 
        $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
        // Optionnel : Garder l'user qui a saisi la mission (Admin/Gestionnaire)
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');

        // Détails du trajet
        $table->string('destination');
        $table->text('motif')->nullable();

        // Chronologie
        $table->datetime('date_depart');
        $table->datetime('date_retour_prevue');
        $table->datetime('date_retour_reelle')->nullable();

        // Suivi kilométrique
        $table->integer('km_depart');
        $table->integer('km_retour')->nullable();

        // État de la mission
        // en_attente, en_cours, terminee, annulee
        $table->string('statut')->default('en_attente');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
