<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultatParametre extends Model
{
    protected $table = 'resultats_parametres';

    protected $fillable = [
        'demande_analyse_id',
        'parametre_id',
        'valeur',
        'hors_norme',
        'saisi_par',
        'saisi_le',
    ];

    protected $casts = [
        'hors_norme' => 'boolean',
        'saisi_le'   => 'datetime',
    ];

    public function demandeAnalyse()
    {
        return $this->belongsTo(DemandeAnalyse::class);
    }

    public function parametre()
    {
        return $this->belongsTo(ParametreAnalyse::class, 'parametre_id');
    }

    public function saisiPar()
    {
        return $this->belongsTo(User::class, 'saisi_par');
    }
}