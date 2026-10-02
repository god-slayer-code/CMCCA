<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consultation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'patiente_id',
        'medecin_id',
        'rendez_vous_id',
        'service_id',
        'motif',
        'diagnostic',
        'notes',
        'poids',
        'tension',
        'temperature',
        'statut',
        'date_validation',
        'created_by',
    ];

    protected $casts = [
        'date_validation' => 'datetime',
        'poids'           => 'decimal:2',
        'temperature'     => 'decimal:1',
    ];

    public function patiente()
    {
        return $this->belongsTo(Patiente::class);
    }

    public function medecin()
    {
        return $this->belongsTo(User::class, 'medecin_id');
    }

    public function rendezVous()
    {
        return $this->belongsTo(RendezVous::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function estValidee(): bool
    {
        return $this->statut === 'validee';
    }

    public function scopeBrouillons($query)
    {
        return $query->where('statut', 'brouillon');
    }

    public function scopeValidees($query)
    {
        return $query->where('statut', 'validee');
    }
}