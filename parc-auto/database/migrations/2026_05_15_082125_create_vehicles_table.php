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
        $table->string('immatriculation')->unique();
        $table->foreignId('driver_id')->nullable()->constrained()->onDelete('set null');
        $table->string('marque');
        $table->string('modele');
        $table->integer('kilometrage_actuel')->default(0);
        // Statuts : disponible, en_reparation, en_mission, immobilise
        $table->string('statut')->default('disponible');
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
