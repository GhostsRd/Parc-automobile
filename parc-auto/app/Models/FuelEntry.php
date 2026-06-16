<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FuelEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'date_ravitaillement',
        'quantite_litres',
        'cout_total',
        'prix_unitaire',
        'kilometrage',
        'station_service',
        'description',
        'consommation_calculee',
        'anomalie_detectee'
    ];

    /**
     * Récupérer le véhicule associé à ce plein.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}