<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = [
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

    public function examens()
{
    return $this->belongsToMany(Analyse::class, 'acte_examens')
                ->withPivot('obligatoire');
}
}