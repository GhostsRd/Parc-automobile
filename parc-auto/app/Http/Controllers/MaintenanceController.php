<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maintenance;
use App\Models\Vehicle;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $maintenances = Maintenance::with('vehicle')->paginate(10);
        return view('maintenances.index', compact('maintenances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
     $vehicles = Vehicle::orderBy('immatriculation')->get();
    return view('maintenances.create', compact('vehicles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'vehicle_id'                     => 'required|exists:vehicles,id',
        'type_entretien'                 => 'required|string', // Vient du formulaire
        'date_maintenance'               => 'required|date',   // Vient du formulaire
        'kilometrage'                    => 'required|integer',// Vient du formulaire
        'cout'                           => 'required|numeric',
        'description'                    => 'nullable|string', // Vient du formulaire
    ]);

    try {
        \DB::transaction(function () use ($validated) {
            
            // On mappe les noms du formulaire vers les noms de la base de données
            $maintenanceData = [
                'vehicle_id'                      => $validated['vehicle_id'],
                'type'                            => $validated['type_entretien'], // 'type_entretien' -> 'type'
                'date_intervention'               => $validated['date_maintenance'], // 'date_maintenance' -> 'date_intervention'
                'kilometrage_au_moment_de_l_acte' => $validated['kilometrage'],      // 'kilometrage' -> 'kilometrage_au_moment_de_l_acte'
                'cout'                            => $validated['cout'],
                'notes'                           => $validated['description'],     // 'description' -> 'notes'
            ];

            // Création
            \App\Models\Maintenance::create($maintenanceData);

            // Mise à jour du véhicule
            $vehicle = \App\Models\Vehicle::findOrFail($validated['vehicle_id']);
            
            // Vérifie si ta colonne dans la table 'vehicles' est 'kilometrage' ou 'kilometrage_actuel'
            if ($validated['kilometrage'] > $vehicle->kilometrage) {
                $vehicle->update(['kilometrage' => $validated['kilometrage']]);
            }
        });

        return redirect()->route('maintenances.index')->with('success', 'Maintenance enregistrée !');

    } catch (\Exception $e) {
        dd($e->getMessage()); // Pour voir s'il reste une autre erreur de nom de colonne
    }
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       $vehicle->load('maintenances'); // Charge les maintenances liées
        return view('vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
