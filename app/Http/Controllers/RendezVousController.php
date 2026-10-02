<?php

namespace App\Http\Controllers;

use App\Models\RendezVous;
use App\Models\Patiente;
use App\Models\User;
use App\Services\RendezVousService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RendezVousController extends Controller
{
    public function __construct(private RendezVousService $service) {}

    public function index(Request $request)
    {
        $date = $request->date ?? today()->format('Y-m-d');

        $rendezVous = RendezVous::with(['patiente', 'medecin'])
            ->whereDate('date_heure_prevue', $date)
            ->orderBy('date_heure_prevue')
            ->get();

        return view('rendez-vous.index', compact('rendezVous', 'date'));
    }

   public function create()
{
    $patientes = Patiente::actives()->orderBy('nom')->get();
    $medecins  = \App\Models\Medecin::actifs()->orderBy('nom')->get();
    $services  = \App\Models\Service::actifs()->orderBy('nom')->get();
    return view('rendez-vous.create', compact('patientes', 'medecins', 'services'));
}

    public function store(Request $request)
    {
        $request->validate([
    'patiente_id'       => 'required|exists:patientes,id',
    'medecin_id'        => 'required|exists:medecins,id',
    'motif_id' => 'nullable|exists:services,id',
    'date_heure_prevue' => 'required|date',
    'duree_minutes'     => 'integer|min:5|max:480',
]);

        try {
            $this->service->creerRendezVous($request->all(), auth()->user(), $request->boolean('forcer_malgre_conflit'));
            return redirect()->route('rendez-vous.index')->with('success', 'Rendez-vous créé.');
        } catch (\Exception $e) {
            if (str_starts_with($e->getMessage(), 'CONFLIT:')) {
                $parts = explode(':', $e->getMessage());
                return back()->with('conflit', "Ce créneau est déjà pris par {$parts[2]}")->withInput();
            }
            throw $e;
        }
    }

    public function annuler(Request $request, RendezVous $rendezVous)
    {
        $request->validate(['motif' => 'required|string|max:255']);
        $rendezVous->update([
            'statut'           => 'annule',
            'motif_annulation' => $request->motif,
        ]);
        return back()->with('success', 'Rendez-vous annulé.');
    }

    public function urgence(Request $request)
    {
        $request->validate([
            'patiente_id' => 'required|exists:patientes,id',
            'medecin_id'  => 'required|exists:users,id',
        ]);

        $this->service->creerRendezVous([
            'patiente_id'       => $request->patiente_id,
            'medecin_id'        => $request->medecin_id,
            'date_heure_prevue' => now(),
            'duree_minutes'     => 30,
            'statut'            => 'urgence',
        ], auth()->user(), true);

        return redirect()->route('rendez-vous.index')->with('success', "Rendez-vous d'urgence créé.");
    }
}