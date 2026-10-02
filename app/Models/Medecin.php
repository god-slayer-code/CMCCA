<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medecin extends Model
{
    protected $fillable = [
        'nom',
        'specialite',
        'telephone',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function rendezVous()
    {
        return $this->hasMany(RendezVous::class, 'medecin_id');
    }

    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }
}