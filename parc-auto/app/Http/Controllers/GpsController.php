<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\GpsPosition;
use App\Models\Geofence;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GpsController extends Controller
{
    /**
     * Vue principale : Carte en temps réel de toute la flotte
     */
    public function index()
    {
        // On récupère le dernier point GPS connu pour chaque véhicule
        $vehicles = Vehicle::all()->map(function($vehicle) {
            $vehicle->derniere_position = GpsPosition::where('vehicle_id', $vehicle->id)
                ->orderBy('timestamp_gps', 'desc')
                ->first();
            return $vehicle;
        });

        return view('gps.index', compact('vehicles'));
    }

    /**
     * Vue Historique d'un trajet pour un véhicule spécifique
     */
    public function history(Request $request, Vehicle $vehicle)
    {
        $date = $request->get('date', Carbon::today()->toDateString());

        // Récupérer toutes les positions du jour choisi
        $positions = GpsPosition::where('vehicle_id', $vehicle->id)
            ->whereDate('timestamp_gps', $date)
            ->orderBy('timestamp_gps', 'asc')
            ->get();

        // Calculs statistiques rapides pour le trajet
        $vitesseMoyenne = $positions->avg('vitesse') ?? 0;
        
        // Calcul du temps d'arrêt (ex: positions successives à 0 km/h)
        $tempsArretMinutes = 0;
        $positionsArret = $positions->where('vitesse', '<=', 2); // Moins de 2km/h = arrêt
        // Logique simplifiée : si un point est à l'arrêt, on estime qu'il y est resté l'intervalle de temps standard

        return view('gps.history', compact('vehicle', 'positions', 'date', 'vitesseMoyenne', 'positionsArret'));
    }

    /**
     * API ENDPOINT : Reçoit le signal GPS du boitier connecté ou de l'application mobile
     */
    public function storePing(Request $request)
    {
        $validated = $request->validate([
            'imei_boitier' => 'required|string', // Identifiant unique du GPS
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'vitesse' => 'required|numeric',
            'timestamp' => 'required',
        ]);

        // Trouver le véhicule lié à ce boitier GPS
        $vehicle = Vehicle::where('imei_gps', $request->imei_boitier)->firstOrFail();

        $statut = ($request->vitesse > 2) ? 'en_mouvement' : 'à l\'arrêt';

        // Enregistrer la position
        $position = GpsPosition::create([
            'vehicle_id' => $vehicle->id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'vitesse' => $request->vitesse,
            'statut' => $statut,
            'timestamp_gps' => Carbon::parse($request->timestamp),
        ]);

        // VÉRIFICATION DU GEOFENCING (Sortie de zone)
        $this->checkGeofencing($vehicle, $position);

        return response()->json(['status' => 'success', 'message' => 'Position enregistrée']);
    }

    /**
     * Algorithme de calcul de distance (Haversine) pour la sortie de zone
     */
    private function checkGeofencing($vehicle, $position)
    {
        $geofences = Geofence::where('actif', true)->get();

        foreach ($geofences as $zone) {
            // Formule mathématique simplifiée pour calculer la distance entre deux points GPS en mètres
            $earthRadius = 6371000;
            $latFrom = deg2rad($zone->centre_latitude);
            $lonFrom = deg2rad($zone->centre_longitude);
            $latTo = deg2rad($position->latitude);
            $lonTo = deg2rad($position->longitude);

            $latDelta = $latTo - $latFrom;
            $lonDelta = $lonTo - $lonFrom;

            $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
            $distance = $angle * $earthRadius;

            if ($distance > $zone->rayon_metres) {
                // Le véhicule est sorti de la zone autorisée ! 
                // Créer ici une notification d'alerte, un email ou un SMS d'alarme
            }
        }
    }
}