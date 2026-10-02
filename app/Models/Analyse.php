<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Analyse extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'categorie',
        'nom',
        'prix_standard',
        'actif',
    ];

    protected $casts = [
        'prix_standard' => 'decimal:2',
        'actif'         => 'boolean',
    ];

    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }
}