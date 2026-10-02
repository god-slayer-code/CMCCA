<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $fillable = [
        'facture_id',
        'montant',
        'mode_paiement',
        'reference',
        'enregistre_par',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
    ];

    public function facture()
    {
        return $this->belongsTo(Facture::class);
    }

    public function enregistrePar()
    {
        return $this->belongsTo(User::class, 'enregistre_par');
    }
}