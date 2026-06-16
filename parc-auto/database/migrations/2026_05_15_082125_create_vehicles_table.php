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
       Schema::create('vehicles', function (Blueprint $table) {
            $table->id();

            // Informations véhicule
            $table->string('immatriculation')->unique();
            $table->string('marque');
            $table->string('modele');
            $table->integer('annee')->nullable();
            $table->string('numero_chassis')->unique()->nullable();
            $table->string('numero_moteur')->nullable();
            $table->string('type_carburant')->nullable(); // Exemple : Essence, Diesel, Electrique
            $table->decimal('capacite_reservoir', 8, 2)->nullable(); // en litre

            // 3.4 Suivi du Kilométrage (Directement intégré ici)
            $table->integer('kilometrage_initial')->default(0);
            $table->integer('kilometrage_actuel')->default(0);
            $table->integer('kilometrage_journalier')->default(0); // Stocke la distance du dernier trajet
            $table->json('historique_kilometrage')->nullable();     // Stocke tous les anciens relevés
            $table->date('date_dernier_releve')->nullable();        // Date du dernier relevé de compteur

            // Affectation
            $table->enum('zone_affectation', [
                'urbaine',
                'regionale',
                'nationale'
            ])->nullable();

            // Chauffeur affecté / Conducteur responsable
            $table->foreignId('driver_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null');

            // Statut véhicule
            $table->enum('statut', [
                'actif',
                'en_maintenance',
                'hors_service',
                'en_mission',
                'en_reservation',
                'immobilise'
            ])->default('actif');

            $table->timestamps();
        });
    }
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
