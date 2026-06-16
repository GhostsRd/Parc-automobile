<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use App\Models\GpsPosition;
use Carbon\Carbon;

class GpsPositionSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = Vehicle::all();

        if ($vehicles->isEmpty()) {
            $this->command->warn("Veuillez d'abord créer des véhicules dans votre base de données !");
            return;
        }

        // Point de départ de simulation : Antananarivo, Madagascar
        $startLat = -18.8792;
        $startLng = 47.5079;

        foreach ($vehicles as $index => $vehicle) {
            $now = Carbon::now();
            
            // On crée 10 points successifs pour simuler un trajet
            for ($i = 10; $i >= 0; $i--) {
                GpsPosition::create([
                    'vehicle_id' => $vehicle->id,
                    // Légère variation pour disperser les véhicules sur la carte de Mada
                    'latitude' => $startLat + ($index * 0.02) + ((10 - $i) * 0.0015),
                    'longitude' => $startLng + ($index * 0.02) + ((10 - $i) * 0.0025),
                    'vitesse' => ($i === 0) ? 0 : rand(20, 60), // Dernier point arrêté, les autres roulent
                    'statut' => ($i === 0) ? 'à l\'arrêt' : 'en_mouvement',
                    'timestamp_gps' => $now->copy()->subMinutes($i * 10),
                ]);
            }
        }

        $this->command->info("Positions GPS simulées à Madagascar avec succès !");
    }
}