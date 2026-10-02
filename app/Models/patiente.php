<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patiente extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'numero_dossier',
        'nom',
        'prenom',
        'noms_precedents',
        'date_naissance',
        'annee_naissance_approximative',
        'date_naissance_approximative',
        'adresse',
        'telephone',
        'contact_alternatif_nom',
        'contact_alternatif_lien',
        'groupe_sanguin',
        'statut',
        'date_deces',
        'responsable_legal_nom',
        'est_mineure',
        'identite_incertaine',
        'motif_visite_id',
        'motif_visite_prix',
        'est_assuree',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'noms_precedents'            => 'array',
        'date_naissance'             => 'date',
        'date_naissance_approximative' => 'boolean',
        'est_mineure'                => 'boolean',
        'identite_incertaine'        => 'boolean',
        'est_assuree'                => 'boolean',
        'motif_visite_prix'           => 'decimal:2',
    ];

    // Âge calculé — jamais stocké en base
    public function getAgeAttribute(): ?int
    {
        if ($this->date_naissance) {
            return $this->date_naissance->age;
        }
        if ($this->annee_naissance_approximative) {
            return now()->year - $this->annee_naissance_approximative;
        }
        return null;
    }

    public function getNomCompletAttribute(): string
    {
        return "{$this->prenom} {$this->nom}";
    }

    public function scopeActives($query)
    {
        return $query->where('statut', 'active');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function assurances()
{
    return $this->hasMany(PatienteAssurance::class);
}

public function estAssuree(): bool
{
    return $this->assurances()
                ->where('actif', true)
                ->exists();
}

    public function motifVisite()
    {
        return $this->belongsTo(Service::class, 'motif_visite_id');
    }

    public function consultations()
{
    return $this->hasMany(Consultation::class);
}
}