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
    // Table des Chauffeurs complète
    Schema::create('drivers', function (Blueprint $table) {
        $table->id();
        $table->string('matricule')->unique();
        $table->string('full_name');
        $table->string('cin')->unique();
        $table->string('phone')->nullable();
        $table->text('address')->nullable();
        
        // Suivi du Permis de conduire
        $table->string('license_number')->nullable();
        $table->string('license_category'); // Ex: B, C, D, E
        $table->date('license_issued_at');
        $table->date('license_expires_at');
        
        // Statut de l'employé
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });

    // Table Pivot pour l'affectation à un ou plusieurs véhicules
    
    }

    public function down(): void
    {
    
        Schema::dropIfExists('drivers');
    }

};
