<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Geofence extends Model
{
    public function up(): void
{
    Schema::create('geofences', function (Blueprint $table) {
        $table->id();
        $table->string('nom');
        $table->decimal('centre_latitude', 10, 8);
        $table->decimal('centre_longitude', 11, 8);
        $table->decimal('rayon_metres', 8, 2); // Rayon d'action circulaire autour du centre
        $table->boolean('actif')->default(true);
        $table->timestamps();
    });
}
}
