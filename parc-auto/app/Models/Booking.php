<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
    'driver_id', 
    'vehicle_id', 
    'user_id', 
    'destination', 
    'motif', 
    'date_depart', 
    'date_retour_prevue', 
    'date_retour_reelle', 
    'km_depart', 
    'km_retour', 
    'statut'
];
public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Relation vers le Chauffeur
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }
}
