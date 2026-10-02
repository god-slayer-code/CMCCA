<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\AuditObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Login;
use App\Listeners\LogConnexion;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

   public function boot(): void
{
    User::observe(AuditObserver::class);
    Event::listen(Login::class, LogConnexion::class);
    \App\Models\Patiente::observe(AuditObserver::class);
    \App\Models\Facture::observe(AuditObserver::class);
    \App\Models\Consultation::observe(AuditObserver::class);
    \App\Models\RendezVous::observe(AuditObserver::class);
    \App\Models\DemandeAnalyse::observe(AuditObserver::class);
    \App\Models\ResultatAnalyse::observe(AuditObserver::class);
    \App\Models\Paiement::observe(AuditObserver::class);
    \App\Models\PatienteAssurance::observe(AuditObserver::class);
    \Illuminate\Support\Facades\Gate::policy(\App\Models\Facture::class, \App\Policies\FacturePolicy::class);
}
}