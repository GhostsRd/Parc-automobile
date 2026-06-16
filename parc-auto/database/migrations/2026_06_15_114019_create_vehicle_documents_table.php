<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_documents', function (Blueprint $table) {
            $table->id();
            // Lien avec le véhicule
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            
            // Type de document restreint à ta liste
            $table->enum('type', [
                'assurance', 
                'visite_technique', 
                'licence', 
                'carte_grise', 
                'patente', 
                'carte_automobile'
            ]);

            $table->string('document_number'); // Numéro du document
            $table->date('issued_at');         // Date de délivrance
            $table->date('expires_at')->nullable(); // Date d'expiration (fortement conseillé pour l'assurance/visite !)
            
            $table->timestamps();

            // Un véhicule ne peut avoir qu'un seul document actif par type
            $table->unique(['vehicle_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_documents');
    }
};