<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GpsPosition extends Model
{
    protected $fillable = ['vehicle_id', 'latitude', 'longitude', 'vitesse', 'statut', 'timestamp_gps'];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}