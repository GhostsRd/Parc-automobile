<?php
namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::withCount('maintenances')->orderBy('full_name')->paginate(10);
        return view('drivers.index', compact('drivers'));
    }

    public function create()
    {
        $vehicles = Vehicle::all(); // On récupère tous les véhicules du parc
        return view('drivers.create', compact('vehicles'));
       // return view('drivers.create');
    }

    public function store(Request $request)
{
    // 1. Validation stricte de toutes les données du formulaire amélioré
    $validated = $request->validate([
        'matricule'        => 'required|string|max:50|unique:drivers,matricule',
        'full_name'        => 'required|string|max:255',
        'cin'              => 'required|string|max:50|unique:drivers,cin',
        'phone'            => 'nullable|string|max:30',
        'address'          => 'nullable|string|max:500',
        'license_number'   => 'required|string|max:50|unique:drivers,license_number',
        'license_category' => 'required|string|in:B,C,D,E',
        'license_issued_at'=> 'required|date',
        'license_expires_at'=> 'required|date|after:license_issued_at',
        'is_active'        => 'required|boolean',
        'vehicles'         => 'nullable|array',
        'vehicles.*'       => 'exists:vehicles,id', // Vérifie que chaque ID de véhicule existe en BDD
    ]);

    // 2. Création du profil du chauffeur
    $driver = Driver::create([
        'matricule'          => $validated['matricule'],
        'full_name'          => $validated['full_name'],
        'cin'                => $validated['cin'],
        'phone'              => $validated['phone'],
        'address'            => $validated['address'],
        'license_number'     => $validated['license_number'],
        'license_category'   => $validated['license_category'],
        'license_issued_at'  => $validated['license_issued_at'],
        'license_expires_at' => $validated['license_expires_at'],
        'is_active'          => $validated['is_active'],
    ]);

    // 3. Affectation aux véhicules (Table Pivot)
    // Si aucune case n'est cochée, un tableau vide [] est envoyé pour nettoyer les liaisons
    $driver->vehicles()->sync($request->input('vehicles', []));

    // 4. Redirection vers l'index avec une notification flash de succès
    return redirect()->route('drivers.index')
        ->with('success', "Le chauffeur {$driver->full_name} a été enregistré et affecté avec succès.");
}

    public function edit(Driver $driver)
    {
        return view('drivers.edit', compact('driver'));
    }

    public function update(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'license_number' => 'nullable|string|max:50',
            'is_active' => 'required|boolean',
        ]);

        $driver->update($validated);

        return redirect()->route('drivers.index')
            ->with('success', 'Fiche chauffeur mise à jour.');
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();
        return redirect()->route('drivers.index')
            ->with('success', 'Chauffeur supprimé.');
    }
}