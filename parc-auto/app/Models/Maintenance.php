<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
  protected $fillable = [
    'vehicle_id', 
    'driver_id',
    'type', 
    'date_intervention', 
    'kilometrage_au_moment_de_l_acte', 
    'prochain_kilometrage_rappel', 
    'cout', 
    'notes'
];

public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
    public function driver()
{
    return $this->belongsTo(Driver::class);
}
}
