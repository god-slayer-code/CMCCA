<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandeAnalyse extends Model
{
    protected $table = 'demandes_analyses';

    protected $fillable = [
        'patiente_id',
        'consultation_id',
        'type_analyse',
        'statut',
        'assignee_a',
        'demande_par',
        'motif_annulation',
        'paiement_valide',
        'date_paiement',
        'valide_par',
        'montant_paye',
    ];

    protected $casts = [
        'paiement_valide' => 'boolean',
        'date_paiement'   => 'datetime',
        'montant_paye'    => 'decimal:2',
    ];

    public function patiente()
    {
        return $this->belongsTo(Patiente::class);
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function assigneA()
    {
        return $this->belongsTo(User::class, 'assignee_a');
    }

    public function demandePar()
    {
        return $this->belongsTo(User::class, 'demande_par');
    }

    public function validePar()
    {
        return $this->belongsTo(User::class, 'valide_par');
    }

    public function resultat()
    {
        return $this->hasOne(ResultatAnalyse::class);
    }

    // Visible par le laborantin seulement si paiement validé
    public function estDisponiblePourLabo(): bool
    {
        return $this->paiement_valide && $this->statut === 'en_attente';
    }
    public function resultatsParametres()
{
    return $this->hasMany(\App\Models\ResultatParametre::class);
}
}