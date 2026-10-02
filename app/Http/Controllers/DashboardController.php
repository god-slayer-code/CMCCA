<?php

namespace App\Http\Controllers;

use App\Models\Patiente;
use App\Models\RendezVous;
use App\Models\Facture;
use App\Models\Consultation;
use App\Models\DemandeAnalyse;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->periode ?? 'jour';

        [$debut, $fin] = match ($periode) {
            'semaine' => [now()->startOfWeek(), now()->endOfWeek()],
            'mois'    => [now()->startOfMonth(), now()->endOfMonth()],
            default   => [now()->startOfDay(), now()->endOfDay()],
        };

        $stats = [
            'patientes_total'      => Patiente::actives()->count(),
            'patientes_periode'    => Patiente::whereBetween('created_at', [$debut, $fin])->count(),
            'rdv_periode'          => RendezVous::whereBetween('date_heure_prevue', [$debut, $fin])->count(),
            'rdv_aujourdhui'       => RendezVous::aujourdhui()->count(),
            'consultations_periode'=> Consultation::whereBetween('created_at', [$debut, $fin])->count(),
            'consultations_brouillon' => Consultation::brouillons()->count(),
            'factures_attente'     => Facture::whereIn('statut', ['en_attente_validation', 'partiellement_payee'])->count(),
            'revenus_periode'      => Facture::where('statut', 'payee')
                                        ->whereBetween('date_validation', [$debut, $fin])
                                        ->sum('montant_final'),
            'analyses_attente_paiement' => DemandeAnalyse::where('paiement_valide', false)->where('statut', 'en_attente')->count(),
            'analyses_attente_labo' => DemandeAnalyse::where('paiement_valide', true)->where('statut', 'en_attente')->count(),
            'urgences_periode'     => RendezVous::whereBetween('date_heure_prevue', [$debut, $fin])->where('statut', 'urgence')->count(),
        ];

        $prochainesRdv = RendezVous::with(['patiente', 'medecin'])
            ->where('date_heure_prevue', '>=', now())
            ->whereIn('statut', ['planifie', 'confirme'])
            ->orderBy('date_heure_prevue')
            ->limit(5)
            ->get();

        // Flux de patients — 7 derniers jours
        $flux7jours = [];
        for ($i = 6; $i >= 0; $i--) {
            $jour = now()->subDays($i);
            $flux7jours[] = [
                'label' => $jour->translatedFormat('D'),
                'date'  => $jour->format('d/m'),
                'total' => Patiente::whereDate('created_at', $jour)->count(),
            ];
        }

        // Répartition par type d'acte/service (sur la période)
        $repartitionActes = Consultation::whereBetween('consultations.created_at', [$debut, $fin])
            ->join('services', 'consultations.service_id', '=', 'services.id')
            ->selectRaw('services.nom, count(*) as total')
            ->groupBy('services.nom')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

$annonces = \App\Models\Annonce::actives()->orderBy('date_debut', 'desc')->get();

        return view('dashboard', compact('stats', 'periode', 'prochainesRdv', 'flux7jours', 'repartitionActes', 'annonces'));
    }
}