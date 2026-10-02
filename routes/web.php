<?php

use App\Http\Controllers\PatienteController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\LaboratoireController;
use App\Http\Controllers\RendezVousController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\AuditController;  
use App\Http\Controllers\ConfigurationController;     
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Patientes — Super Admin, Admin, Secrétaire
    Route::middleware(['role:super_admin,admin,secretaire'])->group(function () {
        Route::get('/patientes-recherche-ajax', [PatienteController::class, 'rechercheAjax'])->name('patientes.recherche-ajax');
        Route::resource('patientes', PatienteController::class);
        Route::put('/patientes/{patiente}/assurance', [PatienteController::class, 'updateAssurance'])->name('patientes.update-assurance');
    });

// Espace Médecin — Consultations
Route::middleware(['role:super_admin,admin'])->group(function () {
    Route::get('/consultations', [ConsultationController::class, 'index'])->name('consultations.index');
    Route::get('/consultations/{patiente}', [ConsultationController::class, 'show'])->name('consultations.show');
    Route::post('/consultations', [ConsultationController::class, 'store'])->name('consultations.store');
    Route::post('/consultations/{consultation}/valider', [ConsultationController::class, 'valider'])->name('consultations.valider');
    Route::get('/consultations/{consultation}/imprimer', [ConsultationController::class, 'imprimer'])->name('consultations.imprimer');
    Route::post('/laboratoire/creer-demande', [LaboratoireController::class, 'creerDemande'])->name('laboratoire.creer-demande');
     });


// Laboratoire
Route::middleware(['role:super_admin,admin,laborantin'])->group(function () {
    Route::get('/laboratoire', [LaboratoireController::class, 'index'])->name('laboratoire.index');
    Route::get('/laboratoire/resultats', [LaboratoireController::class, 'resultats'])->name('laboratoire.resultats');
    Route::post('/laboratoire/{demande}/resultat', [LaboratoireController::class, 'saisirResultat'])->name('laboratoire.saisir-resultat');
Route::get('/laboratoire/{demande}/saisir-resultats', [LaboratoireController::class, 'voirParametres'])->name('laboratoire.voir-parametres');
Route::post('/laboratoire/{demande}/saisir-resultats', [LaboratoireController::class, 'saisirResultats'])->name('laboratoire.saisir-resultats');
 });


// Comptabilité — Factures
Route::middleware(['role:super_admin,admin,secretaire'])->group(function () {
    Route::get('/factures/create', [FactureController::class, 'create'])->name('factures.create');
    Route::middleware(['role:super_admin,admin'])->group(function () {
    Route::post('/factures/{facture}/appliquer-remise', [FactureController::class, 'appliquerRemise'])->name('factures.appliquer-remise');
});
    Route::post('/factures', [FactureController::class, 'store'])->name('factures.store');
});

Route::middleware(['role:super_admin,admin,comptable'])->group(function () {
    Route::get('/factures', [FactureController::class, 'index'])->name('factures.index');
    Route::post('/factures/{facture}/valider-paiement', [FactureController::class, 'validerPaiement'])->name('factures.valider-paiement');
    Route::get('/factures/{facture}/recu', [FactureController::class, 'recu'])->name('factures.recu');
    Route::post('/factures/{facture}/annuler', [FactureController::class, 'annuler'])->name('factures.annuler');
    Route::get('/factures/historique', [FactureController::class, 'historique'])->name('factures.historique');
});

// Comptable — validation paiement analyses
Route::middleware(['role:super_admin,admin,comptable'])->group(function () {
    Route::get('/laboratoire/en-attente-paiement', [LaboratoireController::class, 'enAttentePaiement'])->name('laboratoire.en-attente-paiement');
    Route::post('/laboratoire/{demande}/valider-paiement', [LaboratoireController::class, 'validerPaiement'])->name('laboratoire.valider-paiement');
});

 // Rendez-vous — Super Admin, Admin, Secrétaire
Route::middleware(['role:super_admin,admin,secretaire'])->group(function () {
    Route::get('/rendez-vous', [RendezVousController::class, 'index'])->name('rendez-vous.index');
    Route::get('/rendez-vous/create', [RendezVousController::class, 'create'])->name('rendez-vous.create');
    Route::post('/rendez-vous', [RendezVousController::class, 'store'])->name('rendez-vous.store');
    Route::post('/rendez-vous/{rendezVous}/annuler', [RendezVousController::class, 'annuler'])->name('rendez-vous.annuler');
    Route::post('/rendez-vous/urgence', [RendezVousController::class, 'urgence'])->name('rendez-vous.urgence');
});

// Gestion des annonces — Super Admin, Admin
Route::middleware(['role:super_admin,admin'])->group(function () {
    Route::get('/annonces', [AnnonceController::class, 'index'])->name('annonces.index');
    Route::get('/annonces/create', [AnnonceController::class, 'create'])->name('annonces.create');
    Route::post('/annonces', [AnnonceController::class, 'store'])->name('annonces.store');
    Route::post('/annonces/{annonce}/toggle-actif', [AnnonceController::class, 'toggleActif'])->name('annonces.toggle-actif');
    Route::delete('/annonces/{annonce}', [AnnonceController::class, 'destroy'])->name('annonces.destroy');
});

// Journal d'audit — Super Admin uniquement
Route::middleware(['role:super_admin'])->group(function () {
    Route::get('/journal-audit', [AuditController::class, 'index'])->name('audit.index');
});

// Configuration de la clinique
Route::middleware(['role:super_admin,admin'])->group(function () {
    Route::get('/configuration', [ConfigurationController::class, 'index'])->name('configuration.index');
    Route::put('/configuration', [ConfigurationController::class, 'update'])->name('configuration.update');
});

// Gestion des comptes utilisateurs — Super Admin
Route::middleware(['role:super_admin'])->group(function () {
    Route::get('/utilisateurs', [UtilisateurController::class, 'index'])->name('utilisateurs.index');
    Route::get('/utilisateurs/create', [UtilisateurController::class, 'create'])->name('utilisateurs.create');
    Route::post('/utilisateurs', [UtilisateurController::class, 'store'])->name('utilisateurs.store');
    Route::get('/utilisateurs/{utilisateur}/edit', [UtilisateurController::class, 'edit'])->name('utilisateurs.edit');
    Route::put('/utilisateurs/{utilisateur}', [UtilisateurController::class, 'update'])->name('utilisateurs.update');
    Route::post('/utilisateurs/{utilisateur}/toggle-actif', [UtilisateurController::class, 'toggleActif'])->name('utilisateurs.toggle-actif');
});
});

require __DIR__.'/auth.php';
