<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\JournalAudit;

class LogConnexion
{
    public function handle(Login $event): void
    {
        JournalAudit::create([
            'user_id'           => $event->user->id,
            'action'            => 'connexion',
            'table_concernee'   => 'users',
            'enregistrement_id' => $event->user->id,
            'details_json'      => ['message' => 'Connexion réussie'],
            'ip_adresse'        => request()->ip(),
        ]);
    }
}