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

        $table->string('type_carburant')->nullable(); 
        // Exemple : Essence, Diesel, Electrique

        $table->decimal('capacite_reservoir', 8, 2)->nullable();
        // en litre

        // Kilométrage
        $table->integer('kilometrage_initial')->default(0);

        $table->integer('kilometrage_actuel')->default(0);


        // Affectation
        $table->enum('zone_affectation', [
            'urbaine',
            'regionale',
            'nationale'
        ])->nullable();


        // Chauffeur affecté
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
