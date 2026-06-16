<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleFactory> */
    use HasFactory;

    protected $fillable = [
        'immatriculation',
        'marque',
        'modele',
        'annee',
        'numero_chassis',
        'numero_moteur',
        'type_carburant',
        'capacite_reservoir',
        'kilometrage_initial',
        'kilometrage_actuel',
        'kilometrage_journalier', // Ajouté pour le suivi 3.4
        'historique_kilometrage', // Ajouté pour le suivi 3.4
        'date_dernier_releve',    // Ajouté pour le suivi 3.4
        'zone_affectation',
        'statut',
        'driver_id'
    ];

    /**
     * Casts pour transformer automatiquement les types complexes
     */
    protected $casts = [
        'date_dernier_releve'    => 'date',
        'historique_kilometrage' => 'array', // Crucial pour manipuler le JSON comme un tableau PHP
    ];

    /**
     * ALERTE MAINTENANCE (Seuil : 10 000 km)
     * Calcule si la distance parcourue depuis le kilométrage initial atteint ou dépasse 10 000 km
     */
    public function aBesoinMaintenance(): bool
    {
        $seuil = 10000;
        $kmParcourus = $this->kilometrage_actuel - $this->kilometrage_initial;
        return $kmParcourus >= $seuil;
    }

    /*
    |--------------------------------------------------------------------------
    | Relations Eloquent
    |--------------------------------------------------------------------------
    */

    public function driver(): BelongsTo
    {
        // Retourne le chauffeur actuel s'il y en a un, sinon retourne null
        return $this->belongsTo(Driver::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(VehicleDocument::class);
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class)->orderBy('date_retour_reelle', 'desc');
    }

    /**
 * Récupérer tous les ravitaillements en carburant du véhicule.
 */
    public function fuelEntries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FuelEntry::class);
    }
}