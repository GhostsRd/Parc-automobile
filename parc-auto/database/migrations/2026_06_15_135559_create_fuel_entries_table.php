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
    Schema::create('fuel_entries', function (Blueprint $table) {
        $table->id();
        $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
        $table->date('date_ravitaillement');
        $table->decimal('quantite_litres', 8, 2);
        $table->decimal('cout_total', 10, 2);
        $table->decimal('prix_unitaire', 6, 3); // 3 décimales pour le prix au litre
        $table->integer('kilometrage');
        $table->string('station_service');
        $table->text('description')->nullable();
        $table->decimal('consommation_calculee', 5, 2)->nullable(); // L/100 km
        $table->boolean('anomalie_detectee')->default(false);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuel_entries');
    }
};
