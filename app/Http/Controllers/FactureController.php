<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Paiement;
use App\Models\Patiente;
use App\Models\Service;
use Illuminate\Http\Request;

class FactureController extends Controller
{
    // Liste des factures en attente (pour le comptable)
    public function index()
    {
        $factures = Facture::with(['patiente', 'service', 'enregistreePar'])
            ->whereIn('statut', ['en_attente_validation', 'partiellement_payee'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('factures.index', compact('factures'));
    }

    // Créer une facture (secrétaire)
    public function create()
    {
        $patientes = Patiente::actives()->orderBy('nom')->get();
        $services  = Service::where('actif', true)->orderBy('nom')->get();
        return view('factures.create', compact('patientes', 'services'));
    }

    // Enregistrer une facture
    public function store(Request $request)
    {
        $request->validate([
            'patiente_id' => 'required|exists:patientes,id',
            'service_id'  => 'required|exists:services,id',
        ]);

        $service = Service::findOrFail($request->service_id);

        Facture::create([
            'patiente_id'     => $request->patiente_id,
            'service_id'      => $request->service_id,
            'numero_facture'  => Facture::genererNumero(),
            'montant_service' => $service->prix_standard,
            'montant_final'   => $service->prix_standard,
            'statut'          => 'en_attente_validation',
            'enregistree_par' => auth()->id(),
            'created_by'      => auth()->id(),
        ]);

        return redirect()->route('factures.index')
            ->with('success', 'Facture enregistrée avec succès.');
    }

    public function appliquerRemise(Request $request, Facture $facture)
{
    $this->authorize('appliquerRemise', $facture);

    $request->validate([
        'type_remise'       => 'required|in:pourcentage,montant',
        'valeur_remise'     => 'required|numeric|min:0.01',
        'motif_ajustement'  => 'nullable|string|max:255',
    ]);

    if ($request->type_remise === 'pourcentage') {
        if ($request->valeur_remise > 100) {
            return back()->with('error', 'Le pourcentage ne peut pas dépasser 100%.');
        }
        $nouveauMontant = $facture->montant_service - ($facture->montant_service * $request->valeur_remise / 100);
    } else {
        $nouveauMontant = $facture->montant_service - $request->valeur_remise;
    }

    if ($nouveauMontant < 0) {
        return back()->with('error', 'Le montant après remise ne peut pas être négatif.');
    }

    $facture->update([
        'montant_ajuste'   => round($nouveauMontant, 2),
        'montant_final'    => round($nouveauMontant, 2),
        'ajuste_par'       => auth()->id(),
        'motif_ajustement' => $request->motif_ajustement,
    ]);

    return back()->with('success', 'Remise appliquée — nouveau montant : ' . number_format($nouveauMontant, 0, ',', ' ') . ' GNF.');
}

    // Valider le paiement (comptable)
    public function validerPaiement(Request $request, Facture $facture)
    {
        $request->validate([
            'montant'       => 'required|numeric|min:0|max:99999999',
            'mode_paiement' => 'required|in:especes,mobile_money,carte,autre',
        ]);

        Paiement::create([
            'facture_id'     => $facture->id,
            'montant'        => $request->montant,
            'mode_paiement'  => $request->mode_paiement,
            'reference'      => $request->reference,
            'enregistre_par' => auth()->id(),
        ]);

        $totalPaye = $facture->paiements()->sum('montant') + $request->montant;

        if ($totalPaye >= $facture->montant_final) {
            $facture->update([
                'statut'          => 'payee',
                'validee_par'     => auth()->id(),
                'date_validation' => now(),
            ]);
        } else {
            $facture->update(['statut' => 'partiellement_payee']);
        }

        return redirect()->route('factures.recu', $facture)
            ->with('success', 'Paiement validé.');
    }

    // Afficher le reçu
    public function recu(Facture $facture)
    {
        return view('factures.recu', compact('facture'));
    }

    // Annuler une facture
    public function annuler(Request $request, Facture $facture)
    {
        $request->validate(['motif' => 'required|string|max:255']);

        $facture->update([
            'statut'           => 'annulee',
            'motif_annulation' => $request->motif,
            'annule_par'       => auth()->id(),
        ]);

        return back()->with('success', 'Facture annulée.');
    }

    // Historique de toutes les factures
    public function historique()
    {
        $factures = Facture::with(['patiente', 'service'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('factures.historique', compact('factures'));
    }
}