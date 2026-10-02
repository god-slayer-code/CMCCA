<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParametreAnalyse extends Model
{
    protected $table = 'parametres_analyses';

    protected $fillable = [
        'categorie',
        'nom',
        'unite',
        'normale_min',
        'normale_max',
        'normale_texte',
        'actif',
        'ordre',
    ];

    protected $casts = [
        'normale_min' => 'decimal:4',
        'normale_max' => 'decimal:4',
        'actif'       => 'boolean',
    ];

    public function estHorsNorme(string $valeur): bool
    {
        if (!is_numeric($valeur)) return false;
        if ($this->normale_min === null && $this->normale_max === null) return false;

        $v = (float) $valeur;
        if ($this->normale_min !== null && $v < $this->normale_min) return true;
        if ($this->normale_max !== null && $v > $this->normale_max) return true;

        return false;
    }

    public function scopeActifs($query)
    {
        return $query->where('actif', true)->orderBy('categorie')->orderBy('ordre');
    }
}