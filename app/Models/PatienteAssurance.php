<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatienteAssurance extends Model
{
    protected $table = 'patiente_assurances';

    protected $fillable = [
        'patiente_id',
        'assurance_id',
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
        'created_by',
    ];

    protected $casts = [
        'date_debut'       => 'date',
        'date_fin'         => 'date',
        'taux_couverture'  => 'decimal:2',
        'plafond_montant'  => 'decimal:2',
        'actif'            => 'boolean',
    ];

    public function patiente()
    {
        return $this->belongsTo(Patiente::class);
    }

    public function assurance()
    {
        return $this->belongsTo(Assurance::class);
    }

    // Calcule le montant que la patiente doit payer
    public function calculerPartPatiente(float $montantTotal): float
    {
        $prise_en_charge = $montantTotal * ($this->taux_couverture / 100);

        // Vérifier si la prise en charge dépasse le plafond montant
        if ($this->plafond_montant && $prise_en_charge > $this->plafond_montant) {
            $prise_en_charge = $this->plafond_montant;
        }

        return $montantTotal - $prise_en_charge;
    }

    // Vérifie si l'assurance est encore valide
    public function estValide(): bool
    {
        if (!$this->actif) return false;

        if ($this->date_fin && $this->date_fin->isPast()) return false;

        if ($this->plafond_visites && $this->visites_utilisees >= $this->plafond_visites) {
            return false;
        }

        return true;
    }
}