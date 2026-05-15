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
      $validated = $request->validate([
        'immatriculation'    => 'required|string|unique:vehicles,immatriculation|max:20',
        'marque'             => 'required|string|max:100',
        'modele'             => 'required|string|max:100',
        'kilometrage_actuel' => 'required|integer|min:0',
        'statut'             => 'required|in:disponible,en_reparation,en_mission,immobilise',
        // On vérifie que le driver_id existe bien dans la table 'drivers'
        'driver_id'          => 'nullable|exists:drivers,id', 
    ]);

    // 2. Création du véhicule avec les données validées
    $vehicle = Vehicle::create($validated);

    // 3. Redirection avec message de succès
    return redirect()->route('vehicles.index')
        ->with('success', "Le véhicule {$vehicle->immatriculation} a été enregistré avec succès.");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->load(['maintenances']);
        
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
    public function destroy(string $id)
    {
        $vehicle->delete();
    return redirect()->route('vehicles.index')->with('success', 'Véhicule supprimé.');
    }
}
