<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facture extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'patiente_id',
        'service_id',
        'numero_facture',
        'montant_service',
        'montant_ajuste',
        'montant_final',
        'ajuste_par',
        'motif_ajustement',
        'statut',
        'enregistree_par',
        'validee_par',
        'date_validation',
        'motif_annulation',
        'annule_par',
        'created_by',
    ];

    protected $casts = [
        'montant_service' => 'decimal:2',
        'montant_ajuste'  => 'decimal:2',
        'montant_final'   => 'decimal:2',
        'date_validation' => 'datetime',
    ];

    public function patiente()
    {
        return $this->belongsTo(Patiente::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function enregistreePar()
    {
        return $this->belongsTo(User::class, 'enregistree_par');
    }

    public function validePar()
    {
        return $this->belongsTo(User::class, 'validee_par');
    }

    public function getSoldeDuAttribute(): float
    {
        return $this->montant_final - $this->paiements->sum('montant');
    }

    public function estPayee(): bool
    {
        return $this->statut === 'payee';
    }

    public static function genererNumero(): string
    {
        return 'FAC-' . now()->format('Ymd') . '-' . str_pad(
            self::whereDate('created_at', today())->count() + 1,
            4, '0', STR_PAD_LEFT
        );
    }
}