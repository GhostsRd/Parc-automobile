<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Driver;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicles = Vehicle::all(); 
        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      $drivers = Driver::where('is_active', true)->orderBy('full_name')->get();
       return view('vehicles.create', compact('drivers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // 1. Validation stricte selon les champs du formulaire
    $validated = $request->validate([
        'immatriculation'     => 'required|string|unique:vehicles,immatriculation|max:20',
        'marque'              => 'required|string|max:100',
        'modele'              => 'required|string|max:100',
        'annee'               => 'nullable|integer|min:1900|max:' . date('Y'),
        'numero_chassis'      => 'nullable|string|max:100|unique:vehicles,numero_chassis',
        'numero_moteur'       => 'nullable|string|max:100',
        'type_carburant'      => 'nullable|string|max:50',
        'capacite_reservoir'  => 'nullable|numeric|min:0',
        'kilometrage_initial' => 'required|integer|min:0',
        'kilometrage_actuel'  => 'required|integer|min:0',
        'zone_affectation'    => 'nullable|in:urbaine,regionale,nationale',
        'statut'              => 'required|in:actif,en_maintenance,hors_service',
        'driver_id'           => 'nullable|exists:drivers,id',
    ]);

    try {
        // 2. Création du véhicule en base de données
        $vehicle = Vehicle::create($validated);

        // 3. Redirection vers l'index avec message de succès
        return redirect()->route('vehicles.index')
            ->with('success', "Le véhicule {$vehicle->immatriculation} ({$vehicle->marque} {$vehicle->modele}) a été enregistré avec succès.");

    } catch (\Exception $e) {
        // En cas d'erreur inattendue, on revient en arrière en préservant les saisies
        return back()->withInput()->with('error', 'Erreur lors de la création du véhicule : ' . $e->getMessage());
    }
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->load(['maintenances','bookings','driver']);
        // 2. AJOUT : On récupère la liste de TOUS les chauffeurs actifs pour le formulaire
        $drivers = Driver::where('is_active', true)->get();
        
        // 3. On passe $vehicle ET $drivers à la vue
        return view('vehicles.show', compact('vehicle', 'drivers'));
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
       $validated = $request->validate([
        'immatriculation'    => 'required|string|max:20|unique:vehicles,immatriculation,' . $vehicle->id,
        'marque'             => 'required|string|max:100',
        'modele'             => 'required|string|max:100',
        'kilometrage_actuel' => 'required|integer|min:0',
        'statut'             => 'required|in:disponible,en_reparation,en_mission,immobilise',
        'driver_id'          => 'nullable|exists:drivers,id',
    ]);

    $vehicle->update($validated);

    return redirect()->route('vehicles.show', $vehicle)
        ->with('success', "Mise à jour effectuée.");
    }

    /**
     * Remove the specified resource from storage.
     */
    // On remplace "string $id" par "Vehicle $vehicle"
    public function destroy(Vehicle $vehicle)
    {
        // Plus besoin de chercher, Laravel a déjà chargé le véhicule correspondant !
        $vehicle->delete();

        return redirect()->route('vehicles.index')->with('success', 'Véhicule supprimé avec succès.');
    }
}
