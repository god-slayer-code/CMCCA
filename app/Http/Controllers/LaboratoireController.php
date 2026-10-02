<?php

namespace App\Http\Controllers;

use App\Models\DemandeAnalyse;
use App\Models\ResultatAnalyse;
use Illuminate\Http\Request;

class LaboratoireController extends Controller
{
    // Liste des demandes pour le laborantin (seulement si paiement validé)
    public function index()
    {
        $demandes = DemandeAnalyse::with(['patiente', 'consultation'])
            ->where('paiement_valide', true)
            ->where('statut', 'en_attente')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('laboratoire.index', compact('demandes'));
    }

    public function creerDemande(Request $request)
{
    $request->validate([
        'patiente_id'  => 'required|exists:patientes,id',
        'type_analyse' => 'required|string|max:255',
    ]);

    DemandeAnalyse::create([
        'patiente_id'  => $request->patiente_id,
        'type_analyse' => $request->type_analyse,
        'statut'       => 'en_attente',
        'demande_par'  => auth()->id(),
    ]);

    return back()->with('success', 'Demande d\'analyse envoyée — en attente de paiement.');
}

    // Liste des demandes en attente de paiement (pour le comptable)
    public function enAttentePaiement()
    {
        $demandes = DemandeAnalyse::with(['patiente', 'consultation', 'demandePar'])
            ->where('paiement_valide', false)
            ->where('statut', 'en_attente')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('laboratoire.en-attente-paiement', compact('demandes'));
    }

    // Comptable valide le paiement
    public function validerPaiement(Request $request, DemandeAnalyse $demande)
    {
        $request->validate([
            'montant_paye' => 'required|numeric|min:0|max:99999999',
        ]);

        $demande->update([
            'paiement_valide' => true,
            'date_paiement'   => now(),
            'valide_par'      => auth()->id(),
            'montant_paye'    => $request->montant_paye,
        ]);

        return back()->with('success', 'Paiement validé — la demande est maintenant visible par le laboratoire.');
    }

    // Laborantin saisit le résultat
    // Laborantin voit les paramètres à remplir pour une demande
public function voirParametres(DemandeAnalyse $demande)
{
    if (!$demande->estDisponiblePourLabo()) {
        return back()->with('error', 'Paiement non encore validé.');
    }

    $parametres = \App\Models\ParametreAnalyse::actifs()->get()->groupBy('categorie');
    $resultats  = $demande->resultatsParametres->keyBy('parametre_id');

    return view('laboratoire.saisir-resultats', compact('demande', 'parametres', 'resultats'));
}

// Laborantin saisit tous les résultats
public function saisirResultats(Request $request, DemandeAnalyse $demande)
{
    if (!$demande->estDisponiblePourLabo()) {
        return back()->with('error', 'Paiement non encore validé.');
    }

    foreach ($request->valeurs ?? [] as $parametreId => $valeur) {
        if ($valeur === null || $valeur === '') continue;

        $parametre  = \App\Models\ParametreAnalyse::find($parametreId);
        $horsNorme  = $parametre ? $parametre->estHorsNorme($valeur) : false;

        \App\Models\ResultatParametre::updateOrCreate(
            [
                'demande_analyse_id' => $demande->id,
                'parametre_id'       => $parametreId,
            ],
            [
                'valeur'     => $valeur,
                'hors_norme' => $horsNorme,
                'saisi_par'  => auth()->id(),
                'saisi_le'   => now(),
            ]
        );
    }

    $demande->update(['statut' => 'resultat_saisi']);

    return redirect()->route('laboratoire.index')
        ->with('success', 'Résultats enregistrés avec succès.');
}

    // Tous les résultats
    public function resultats()
    {
        $resultats = ResultatAnalyse::with([
            'demandeAnalyse.patiente',
            'saisiPar'
        ])
        ->orderBy('created_at', 'desc')
        ->get();

        return view('laboratoire.resultats', compact('resultats'));
    }
}