<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Driver;
use Illuminate\Http\Request;

class VehicleMileageController extends Controller
{
    /**
     * Affiche le tableau de bord des kilométrages
     */
    public function index()
    {
       $vehicles = Vehicle::with(['driver', 'bookings.driver'])->get();

        $drivers = \App\Models\Driver::all(); 

        return view('mileage.index', compact('vehicles', 'drivers'));
    }

    /**
     * Permet une mise à jour manuelle/ajustement du compteur (Hors clôture de mission)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id'         => 'required|exists:vehicles,id',
            'driver_id'          => 'required|exists:drivers,id',
            'kilometrage_arrivee'=> 'required|integer',
            'date_releve'        => 'required|date',
        ]);

        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
        $chauffeur = Driver::findOrFail($validated['driver_id']);

        $ancien_km = $vehicle->kilometrage_actuel;
        $nouveau_km = (int) $validated['kilometrage_arrivee'];

        if ($nouveau_km < $ancien_km) {
            return redirect()->back()->withErrors([
                'kilometrage_arrivee' => "Erreur : Le kilométrage entré est inférieur au compteur actuel ({$ancien_km} km)."
            ]);
        }

        // Calcul du kilométrage journalier / distance du trajet
        $distance_parcourue = $nouveau_km - $ancien_km;

        // Historisation JSON
        $historique = $vehicle->historique_kilometrage ?? [];
        $historique[] = [
            'kilometrage_total' => $nouveau_km,
            'distance_parcourue'=> $distance_parcourue,
            'date_releve'       => $validated['date_releve'],
            'chauffeur_nom'     => $chauffeur->full_name, // Stocke le nom en dur en cas de suppression future du chauffeur
        ];

        // Enregistrement
        $vehicle->update([
            'kilometrage_actuel'     => $nouveau_km,
            'kilometrage_journalier' => $distance_parcourue,
            'date_dernier_releve'    => $validated['date_releve'],
            'driver_id'              => $chauffeur->id,
            'historique_kilometrage' => $historique
        ]);

        return redirect()->route('mileage.index')->with('success', 'Compteur kilométrique mis à jour.');
    }
}