<?php

namespace App\Http\Controllers;


use App\Models\Vehicle;
use App\Models\VehicleDocument;
use Illuminate\Http\Request;

class VehicleDocumentController extends Controller
{
    /**
     * Affiche la liste globale de tous les documents par véhicule
     */
    public function index()
    {
        // On récupère tous les véhicules avec leurs documents associés pour éviter les requêtes à répétition (Eager Loading)
        $vehicles = Vehicle::with('documents')->get();
        
        // Liste des types pour gérer facilement l'affichage
        $documentTypes = [
            'assurance'        => 'Assurance',
            'visite_technique' => 'Visite Technique',
            'licence'          => 'Licence',
            'carte_grise'      => 'Carte Grise',
            'patente'          => 'Patente',
            'carte_automobile' => 'Carte Automobile'
        ];

        return view('documents.index', compact('vehicles', 'documentTypes')); //  Séparé par des simples quotes
    }

    /**
     * Enregistre ou met à jour un document pour un véhicule (en AJAX ou formulaire classique)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id'      => 'required|exists:vehicles,id',
            'type'            => 'required|string|in:assurance,visite_technique,licence,carte_grise,patente,carte_automobile',
            'document_number' => 'required|string|max:100',
            'issued_at'       => 'required|date',
            'expires_at'      => 'nullable|date|after:issued_at',
        ]);

        // updateOrCreate permet de mettre à jour le document s'il existe déjà pour ce véhicule, sinon il le crée !
        VehicleDocument::updateOrCreate(
            [
                'vehicle_id' => $validated['vehicle_id'],
                'type' => $validated['type']
            ],
            [
                'document_number' => $validated['document_number'],
                'issued_at' => $validated['issued_at'],
                'expires_at' => $validated['expires_at'],
            ]
        );

        return redirect()->back()->with('success', 'Document administratif mis à jour avec succès.');
    }
}