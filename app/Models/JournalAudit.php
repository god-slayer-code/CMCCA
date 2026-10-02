<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalAudit extends Model
{
    protected $table = 'journal_audit';
    protected $guarded = ['id'];
    public $timestamps = false;

   protected $casts = [
    'details_json' => 'array',
    'created_at'   => 'datetime',
];

    // Immuable — personne ne peut modifier ni supprimer
    protected static function boot()
    {
        parent::boot();

        static::updating(function () {
            throw new \Exception("Le journal d'audit est immuable !");
        });

        static::deleting(function () {
            throw new \Exception("Le journal d'audit est immuable !");
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}