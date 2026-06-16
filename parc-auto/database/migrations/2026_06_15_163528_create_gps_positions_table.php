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
    Schema::create('gps_positions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
        $table->decimal('latitude', 10, 8);
        $table->decimal('longitude', 11, 8);
        $table->decimal('vitesse', 5, 2)->default(0); // km/h
        $table->string('statut')->default('en_mouvement'); // en_mouvement, à l'arrêt
        $table->timestamp('timestamp_gps'); // L'heure exacte du relevé GPS
        $table->timestamps();

        // Index pour accélérer les recherches historiques
        $table->index(['vehicle_id', 'timestamp_gps']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gps_positions');
    }
};
