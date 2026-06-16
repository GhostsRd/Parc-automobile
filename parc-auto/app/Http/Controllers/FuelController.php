<?php

namespace App\Http\Controllers;
use App\Models\FuelEntry; // Nom supposé du modèle pour les reçus de carburant
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FuelController extends Controller
{
    /**
     * Affiche l'historique des ravitaillements et les rapports de consommation.
     */
   public function index(Request $request)
{
    $selectedVehicleId = $request->get('vehicle_id');
    $search = $request->get('search'); // Récupération du mot-clé (Nom, destination, voyage)
    
    // Récupération de tous les véhicules pour le filtre
    $vehicles = Vehicle::all();

    // Requête de base pour l'historique
    $query = FuelEntry::with('vehicle')->orderBy('date_ravitaillement', 'desc');
    
    // 1. Filtre classique par véhicule s'il est sélectionné
    if ($selectedVehicleId) {
        $query->where('vehicle_id', $selectedVehicleId);
    }

    // 2. Recherche multi-critères (Nom, Destination, Voyage, Commentaires)
    if ($search) {
        $query->where(function($q) use ($search) {
            // Recherche dans les propriétés du véhicule lié
            $q->whereHas('vehicle', function($vQ) use ($search) {
                $vQ->where('marque', 'like', "%{$search}%")
                   ->orWhere('modele', 'like', "%{$search}%")
                   ->orWhere('immatriculation', 'like', "%{$search}%");
            })
            // Cherche dans les colonnes de la table FuelEntry 
            // Note : Ajuste 'destination' ou 'motif_voyage' si tes colonnes ont des noms différents
            ->orWhere('destination', 'like', "%{$search}%")
            ->orWhere('motif_voyage', 'like', "%{$search}%")
            ->orWhere('commentaire', 'like', "%{$search}%");
        });
    }
    
    $fuelEntries = $query->paginate(15);

    // --- STATISTIQUES & RAPPORTS MENSUELS (Année en cours) ---
    // Les statistiques s'adapteront aussi si tu as filtré par véhicule OU par recherche
    $currentYear = Carbon::now()->year;
    $monthlyReports = FuelEntry::select(
            DB::raw('MONTH(date_ravitaillement) as month'),
            DB::raw('SUM(quantite_litres) as total_litres'),
            DB::raw('SUM(cout_total) as total_cost'),
            DB::raw('COUNT(*) as total_refuels')
        )
        ->whereYear('date_ravitaillement', $currentYear)
        ->when($selectedVehicleId, function($q) use ($selectedVehicleId) {
            return $q->where('vehicle_id', $selectedVehicleId);
        })
        ->when($search, function($q) use ($search) {
            return $q->where(function($sub) use ($search) {
                $sub->whereHas('vehicle', function($vQ) use ($search) {
                    $vQ->where('marque', 'like', "%{$search}%")
                       ->orWhere('modele', 'like', "%{$search}%")
                       ->orWhere('immatriculation', 'like', "%{$search}%");
                })
                ->orWhere('destination', 'like', "%{$search}%")
                ->orWhere('motif_voyage', 'like', "%{$search}%")
                ->orWhere('commentaire', 'like', "%{$search}%");
            });
        })
        ->groupBy(DB::raw('MONTH(date_ravitaillement)'))
        ->orderBy('month')
        ->get()
        ->keyBy('month');

    // On passe le mot-clé 'search' à la vue pour qu'il reste écrit dans la barre de recherche
    return view('fuel.index', compact('fuelEntries', 'vehicles', 'selectedVehicleId', 'monthlyReports', 'search'));
}

    /**
     * Formulaire d'ajout d'un nouveau plein.
     */
    public function create()
    {
        $vehicles = Vehicle::all();
        return view('fuel.create', compact('vehicles'));
    }

    /**
     * Enregistre le ravitaillement et analyse s'il y a une anomalie.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'date_ravitaillement' => 'required|date|before_or_equal:today',
            'quantite_litres' => 'required|numeric|min:1',
            'cout_total' => 'required|numeric|min:1',
            'prix_unitaire' => 'required|numeric|min:0.1',
            'kilometrage' => 'required|integer|min:0',
            'station_service' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // 1. Récupérer le véhicule et son dernier plein pour calculer la conso
        $vehicle = Vehicle::findOrFail($request->vehicle_id);
        $previousEntry = FuelEntry::where('vehicle_id', $vehicle->id)
            ->where('kilometrage', '<', $request->kilometrage)
            ->orderBy('kilometrage', 'desc')
            ->first();

        $consommationMoyenne = null;
        $hasAnomalie = false;

        if ($previousEntry) {
            $distanceParcourue = $request->kilometrage - $previousEntry->kilometrage;
            
            if ($distanceParcourue > 0) {
                // Formule standard : (Litres / Distance) * 100
                $consommationMoyenne = ($request->quantite_litres / $distanceParcourue) * 100;
                
                // 2. DÉTECTION D'ANOMALIE
                // Si la consommation dépasse de 30% la consommation théorique du véhicule
                $consoTheorique = $vehicle->consommation_theorique ?? 7.0; // 7L/100 par défaut si non renseigné
                if ($consommationMoyenne > ($consoTheorique * 1.3)) {
                    $hasAnomalie = true;
                }
            }
        }

        // 3. Sauvegarde du ravitaillement
        $fuelEntry = new FuelEntry($validated);
        $fuelEntry->consommation_calculee = $consommationMoyenne;
        $fuelEntry->anomalie_detectee = $hasAnomalie;
        $fuelEntry->save();

        // 4. Mettre à jour le kilométrage actuel du véhicule s'il est plus grand
        if ($request->kilometrage > $vehicle->kilometrage_actuel) {
            $vehicle->update(['kilometrage_actuel' => $request->kilometrage]);
        }

        // Retour avec message flash adapté
        if ($hasAnomalie) {
            return redirect()->route('fuel.index')
                ->with('warning', 'Ravitaillement enregistré. ⚠️ Attention : Une consommation anormale a été détectée pour ce plein (' . round($consommationMoyenne, 2) . ' L/100).');
        }

        return redirect()->route('fuel.index')->with('success', 'Le ravitaillement a bien été enregistré.');
    }

    /**
     * Formulaire d'édition.
     */
    public function edit(FuelEntry $fuel)
    {
        $vehicles = Vehicle::all();
        return view('fuel.edit', compact('fuel', 'vehicles'));
    }

    /**
     * Mise à jour de l'entrée.
     */
    public function update(Request $request, FuelEntry $fuel)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'date_ravitaillement' => 'required|date',
            'quantite_litres' => 'required|numeric|min:1',
            'cout_total' => 'required|numeric|min:1',
            'prix_unitaire' => 'required|numeric|min:0.1',
            'kilometrage' => 'required|integer|min:0',
            'station_service' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $fuel->update($validated);

        return redirect()->route('fuel.index')->with('success', 'Ravitaillement mis à jour avec succès.');
    }

    /**
     * Suppression d'un reçu.
     */
    public function destroy(FuelEntry $fuel)
    {
        $fuel->delete();
        return redirect()->route('fuel.index')->with('success', 'Le ravitaillement a été supprimé.');
    }
}