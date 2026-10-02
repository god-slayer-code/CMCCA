<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RendezVous extends Model
{
    use SoftDeletes;

    protected $table = 'rendez_vous';

   protected $fillable = [
    'patiente_id',
    'medecin_id',
    'motif_id',
    'date_heure_prevue',
    'date_heure_reelle',
    'duree_minutes',
    'statut',
    'motif_annulation',
    'reprogramme_depuis_id',
    'confirme',
    'created_by',
];

    protected $casts = [
        'date_heure_prevue'  => 'datetime',
        'date_heure_reelle'  => 'datetime',
        'confirme'           => 'boolean',
    ];

    public function patiente()
    {
        return $this->belongsTo(Patiente::class);
    }

    public function medecin()
    {
        return $this->belongsTo(Medecin::class, 'medecin_id');
    }

    public function reprogrammeDepuis()
    {
        return $this->belongsTo(RendezVous::class, 'reprogramme_depuis_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePlanifies($query)
    {
        return $query->where('statut', 'planifie');
    }

    public function scopeAujourdhui($query)
    {
        return $query->whereDate('date_heure_prevue', today());
    }

    public function motif()
{
    return $this->belongsTo(Service::class, 'motif_id');
}

}