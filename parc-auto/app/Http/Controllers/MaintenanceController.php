<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maintenance;
use Illuminate\Support\Facades\DB;
use App\Models\Booking;
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
        'vehicle_id'       => 'required|exists:vehicles,id',
        'type_entretien'   => 'required|string', 
        'date_maintenance' => 'required|date',   
        'kilometrage'      => 'required|integer',
        'cout'             => 'required|numeric',
        'description'      => 'nullable|string', 
    ]);

    try {
        \DB::transaction(function () use ($validated) {
            
            // Création de l'entretien
            $maintenanceData = [
                'vehicle_id'                      => $validated['vehicle_id'],
                'type'                            => $validated['type_entretien'], 
                'date_intervention'               => $validated['date_maintenance'], 
                'kilometrage_au_moment_de_l_acte' => $validated['kilometrage'],      
                'cout'                            => $validated['cout'],
                'notes'                           => $validated['description'],     
            ];

            \App\Models\Maintenance::create($maintenanceData);

            // Récupération du véhicule
            $vehicle = \App\Models\Vehicle::findOrFail($validated['vehicle_id']);
            $ancienKilometrage = $vehicle->kilometrage_actuel ?? $vehicle->kilometrage_initial ?? 0;

            $updateData = [];

            if ($validated['kilometrage'] >= $ancienKilometrage) {
                $updateData['kilometrage_actuel'] = $validated['kilometrage'];
            }

            // Nettoyage de la chaîne pour la détection
            $typeClean = mb_strtolower($validated['type_entretien'], 'UTF-8');
            
            // Vérification stricte de la syntaxe ici
            if (str_contains($typeClean, 'vidange')) {
                $updateData['kilometrage_initial'] = $validated['kilometrage'];
            }

            if (!empty($updateData)) {
                $vehicle->update($updateData);
            }

            // Gestion du chauffeur de secours
            $driverId = $vehicle->driver_id;
            if (!$driverId) {
                $premierDriver = \App\Models\Driver::first();
                $driverId = $premierDriver ? $premierDriver->id : null;
            }

            if (!$driverId) {
                throw new \Exception("Aucun conducteur en base de données.");
            }

            // Création du trajet d'historique
            \App\Models\Booking::create([
                'vehicle_id'          => $vehicle->id,
                'driver_id'           => $driverId, 
                'destination'         => '🔧 Entretien (' . $validated['type_entretien'] . ')',
                'motif'               => $validated['description'] ?? 'Passage à l\'atelier pour maintenance',
                'km_depart'           => $ancienKilometrage,
                'km_retour'           => $validated['kilometrage'],
                'date_depart'         => $validated['date_maintenance'],
                'date_retour_prevue'  => $validated['date_maintenance'],
                'date_retour_reelle'  => $validated['date_maintenance'],
                'statut'              => 'termine',
            ]);
        });

        return redirect()->route('maintenances.index')->with('success', 'Maintenance enregistrée avec succès !');

    } catch (\Exception $e) {
        dd("Erreur lors de l'enregistrement : " . $e->getMessage()); 
    }
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // On récupère la maintenance spécifique cliquée, avec son véhicule lié
    $maintenance = Maintenance::with('vehicle')->findOrFail($id);
    
    return view('maintenances.show', compact('maintenance'));
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
