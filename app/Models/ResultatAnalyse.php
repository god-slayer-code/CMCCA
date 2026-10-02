<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultatAnalyse extends Model
{
    protected $table = 'resultats_analyses';

    protected $fillable = [
        'demande_analyse_id',
        'resultat',
        'valeur_hors_norme',
        'saisi_par',
    ];

    protected $casts = [
        'valeur_hors_norme' => 'boolean',
    ];

    public function demandeAnalyse()
    {
        return $this->belongsTo(DemandeAnalyse::class);
    }

    public function saisiPar()
    {
        return $this->belongsTo(User::class, 'saisi_par');
    }
}