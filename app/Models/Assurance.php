<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assurance extends Model
{
    protected $fillable = [
        'nom',
        'telephone',
        'email',
        'adresse',
        'actif',
    ];

    public function patientes()
    {
        return $this->belongsToMany(Patiente::class, 'patiente_assurances')
                    ->withPivot([
                        'domaine',
                        'taux_couverture',
                        'plafond_montant',
                        'plafond_visites',
                        'visites_utilisees',
                        'date_debut',
                        'date_fin',
                        'numero_police',
                        'actif',
                        'notes',
                    ])
                    ->withTimestamps();
    }
}