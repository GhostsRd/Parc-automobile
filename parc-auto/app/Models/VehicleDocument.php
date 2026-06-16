<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class VehicleDocument extends Model
{
    protected $fillable = [
        'vehicle_id', 
        'type', 
        'document_number', 
        'issued_at', 
        'expires_at'
    ];

    // Cast des dates pour manipuler du Carbon facilement
    protected $casts = [
        'issued_at' => 'date',
        'expires_at' => 'date',
    ];

    /**
     * Relation : Un document appartient à un véhicule
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Traduction propre pour l'affichage dans tes vues
     */
    public function getLabelAttribute(): string
    {
        return match($this->type) {
            'assurance'        => 'Assurance',
            'visite_technique' => 'Visite Technique',
            'licence'          => 'Licence',
            'carte_grise'      => 'Carte Grise',
            'patente'          => 'Patente',
            'carte_automobile' => 'Carte Automobile',
            default            => ucfirst($this->type),
        };
    }
}