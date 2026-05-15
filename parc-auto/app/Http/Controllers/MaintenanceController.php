<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maintenance;

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
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'type' => 'required|string',
            'date_intervention' => 'required|date',
            'kilometrage_au_moment_de_l_acte' => 'required|integer',
            'prochain_kilometrage_rappel' => 'required|integer|gt:kilometrage_au_moment_de_l_acte',
            'cout' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        Maintenance::create($request->all());

        return back()->with('success', 'Maintenance enregistrée !');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      // 1. Validation stricte des données
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'type' => 'required|string',
            'date_intervention' => 'required|date',
            'kilometrage_au_moment_de_l_acte' => 'required|integer',
            'prochain_kilometrage_rappel' => 'required|integer|gt:kilometrage_au_moment_de_l_acte',
            'cout' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        // 2. Création de la maintenance
        Maintenance::create($validated);

        // 3. Logique métier : Si c'est une grosse réparation, on peut changer le statut
        // Optionnel : $vehicle = Vehicle::find($request->vehicle_id);
        // if($request->type == 'reparation') { $vehicle->update(['statut' => 'en_reparation']); }

        // 4. Redirection avec un message flash de succès
        return back()->with('success', 'L\'intervention a été enregistrée avec succès.');
    
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
