<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class Driver extends Model
{
    // Ajout de tous les nouveaux champs obligatoires pour la gestion complète
    protected $fillable = [
        'matricule', 
        'full_name', 
        'cin', 
        'phone', 
        'address', 
        'license_number', 
        'license_category', 
        'license_issued_at', 
        'license_expires_at', 
        'is_active'
    ];

    /**
     * Relation existante : Un chauffeur peut initier plusieurs maintenances
     */
    
    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }

    /**
     * NOUVELLE Relation : Affectation à un ou plusieurs véhicules (Many-to-Many)
     */
    public function vehicles(): BelongsToMany
    {
        return $this->belongsToMany(Vehicle::class);
    }

    /**
     * SYSTÈME D'ALERTE : Vérifie si le permis expire bientôt (par défaut dans les 30 prochains jours)
     */
    public function isLicenseExpiringSoon(int $days = 30): bool
    {
        if (!$this->license_expires_at) {
            return false;
        }

        $expiryDate = Carbon::parse($this->license_expires_at);
        $now = Carbon::now();

        // Le permis expire bientôt s'il est encore valide mais que l'échéance est proche
        return $expiryDate->isFuture() && $expiryDate->diffInDays($now) <= $days;
    }

    /**
     * SYSTÈME D'ALERTE : Vérifie si le permis est déjà expiré
     */
    public function isLicenseExpired(): bool
    {
        if (!$this->license_expires_at) {
            return false;
        }

        return Carbon::parse($this->license_expires_at)->isPast();
    }

    /**
     * SUIVI DES DOCUMENTS : Calcule le nombre de jours restants avant l'échéance du permis
     */
    public function getLicenseDaysRemainingAttribute(): int
    {
        if (!$this->license_expires_at) {
            return 0;
        }

        $expiryDate = Carbon::parse($this->license_expires_at);
        $now = Carbon::now();

        if ($this->isLicenseExpired()) {
            return -$now->diffInDays($expiryDate); // Retourne un nombre négatif si expiré
        }

        return $now->diffInDays($expiryDate);
    }
}