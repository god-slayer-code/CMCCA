<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActeExamen extends Model
{
    protected $table = 'acte_examens';

    protected $fillable = [
        'service_id',
        'analyse_id',
        'obligatoire',
    ];

    protected $casts = [
        'obligatoire' => 'boolean',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function analyse()
    {
        return $this->belongsTo(Analyse::class);
    }
}