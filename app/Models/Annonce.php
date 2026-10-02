<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Annonce extends Model
{
    protected $fillable = [
        'titre',
        'contenu',
        'image',
        'date_debut',
        'date_fin',
        'actif',
        'created_by',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin'   => 'date',
        'actif'      => 'boolean',
    ];

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Actives = dans la fenêtre de dates ET actif
    public function scopeActives($query)
    {
        return $query->where('actif', true)
            ->where('date_debut', '<=', now())
            ->where(function ($q) {
                $q->whereNull('date_fin')
                  ->orWhere('date_fin', '>=', now());
            });
    }

    // Applique l'expiration automatique de 14 jours si pas de date_fin définie
    public static function boot()
    {
        parent::boot();

        static::creating(function ($annonce) {
            if (!$annonce->date_fin) {
                $annonce->date_fin = Carbon::parse($annonce->date_debut ?? now())->addDays(14);
            }
        });
    }
}