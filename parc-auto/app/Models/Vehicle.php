<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleFactory> */
    use HasFactory;

    protected $fillable = [
        'immatriculation',
        'driver_id', 
        'marque',
        'modele',
        'statut',
        'kilometrage_actuel', // Assurez-vous que ce nom correspond à votre migration
    ];

    /**
     * Optionnel : Définir les relations pour plus tard
     */
    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    public function driver()
{
    // Retourne le chauffeur actuel s'il y en a un, sinon retourne null
    return $this->belongsTo(Driver::class);
}
}
