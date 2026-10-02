<?php

namespace App\Http\Controllers;

use App\Models\Patiente;
use App\Services\PatienteService;
use Illuminate\Http\Request;

class PatienteController extends Controller
{
    public function __construct(private PatienteService $service) {}

    public function index(Request $request)
    {
        $patientes = Patiente::when($request->recherche, function ($q) use ($request) {
            $terme = $request->recherche;
            $q->whereRaw('SOUNDEX(nom) = SOUNDEX(?)', [$terme])
              ->orWhere('nom', 'like', "%{$terme}%")
              ->orWhere('prenom', 'like', "%{$terme}%")
              ->orWhere('telephone', 'like', "%{$terme}%")
              ->orWhere('numero_dossier', 'like', "%{$terme}%");
        })
        ->where('statut', 'active')
        ->paginate(20);

        return view('patientes.index', compact('patientes'));
    }

    public function rechercheAjax(Request $request)
{
    $terme = $request->input('q', '');

    if (strlen($terme) < 2) {
        return response()->json([]);
    }

    $patientes = Patiente::where('statut', 'active')
        ->where(function ($query) use ($terme) {
            $query->where('nom', 'like', "%{$terme}%")
                  ->orWhere('prenom', 'like', "%{$terme}%")
                  ->orWhere('telephone', 'like', "%{$terme}%")
                  ->orWhere('numero_dossier', 'like', "%{$terme}%");
        })
        ->limit(10)
        ->get(['id', 'nom', 'prenom', 'numero_dossier', 'telephone', 'date_naissance']);

    return response()->json($patientes->map(function ($p) {
        return [
            'id'    => $p->id,
            'label' => "{$p->nom_complet} — {$p->numero_dossier}" .
                       ($p->telephone ? " — {$p->telephone}" : '') .
                       ($p->date_naissance ? " — né(e) le " . $p->date_naissance->format('d/m/Y') : ''),
        ];
    }));
}

    public function create()
    {
        $services = \App\Models\Service::where('actif', true)->orderBy('nom')->get();
        return view('patientes.create', compact('services'));
    }

   public function store(Request $request)
{
    $request->validate([
        'nom'    => 'required|string|max:100',
        'prenom' => 'required|string|max:100',
        'telephone' => 'nullable|string|max:20',
        'date_naissance' => 'nullable|date|before:today',
        'age_approximatif' => 'nullable|integer|min:0|max:120',
        'groupe_sanguin' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
        'est_mineure' => 'boolean',
        'responsable_legal_nom' => 'required_if:est_mineure,true|nullable|string|max:100',
    ]);

    $donnees = [
        'nom'                   => $request->input('nom'),
        'prenom'                => $request->input('prenom'),
        'telephone'             => (string) $request->input('telephone'),
        'date_naissance'        => $request->input('date_naissance'),
        'annee_naissance_approximative' => $request->input('age_approximatif') 
    ? now()->year - (int) $request->input('age_approximatif') 
    : null,
'date_naissance_approximative' => $request->filled('age_approximatif') && !$request->filled('date_naissance'),
        'groupe_sanguin'        => $request->input('groupe_sanguin'),
        'adresse'               => $request->input('adresse'),
        'est_mineure'           => $request->boolean('est_mineure'),
        'responsable_legal_nom' => $request->input('responsable_legal_nom'),
        'motif_visite_id'       => $request->input('motif_visite_id') ?: null,
'motif_visite_prix'     => $request->input('motif_visite_prix') 
                            ?: ($request->input('motif_visite_id') 
                                ? \App\Models\Service::find($request->input('motif_visite_id'))?->prix_standard 
                                : null),
        'est_assuree'           => $request->boolean('est_assuree'), 
    ];

    $doublons = $this->service->chercherDoublons(
        $donnees['nom'],
        $donnees['prenom'],
        $donnees['telephone'],
        $donnees['date_naissance']
    );

    if ($doublons->isNotEmpty() && !$request->confirmer_malgre_doublon) {
        return back()
            ->with('doublons_potentiels', $doublons)
            ->with('warning', 'Des fiches similaires existent. Vérifiez avant de continuer.')
            ->withInput();
    }

    $patiente = $this->service->creerPatiente($donnees, auth()->user());

    // Sauvegarder les détails de l'assurance si assurée
    if ($request->boolean('est_assuree') && $request->input('assurance_nom')) {
        \App\Models\PatienteAssurance::create([
            'patiente_id'     => $patiente->id,
            'assurance_id'    => null,
            'domaine'         => 'tout',
            'taux_couverture' => $request->input('assurance_taux') ?? 0,
            'numero_police'   => $request->input('assurance_numero_police'),
            'date_fin'        => $request->input('assurance_date_fin') ?: null,
            'actif'           => true,
            'notes'           => $request->input('assurance_nom'),
            'created_by'      => auth()->id(),
        ]);
    }

    return redirect()->route('patientes.show', $patiente)
        ->with('success', "Fiche créée : {$patiente->numero_dossier}");
}

    public function show(Patiente $patiente)
    {
        return view('patientes.show', compact('patiente'));
    }

    public function edit(Patiente $patiente)
    {
        return view('patientes.edit', compact('patiente'));
    }

    public function update(Request $request, Patiente $patiente)
    {
        $request->validate([
            'nom'    => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'groupe_sanguin' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
        ]);

        $patiente->update([
            'nom' => $request->input('nom'),
            'prenom' => $request->input('prenom'),
            'telephone' => (string) $request->input('telephone'),
            'adresse' => $request->input('adresse'),
            'groupe_sanguin' => $request->input('groupe_sanguin'),
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('patientes.show', $patiente)
            ->with('success', 'Fiche mise à jour.');
    }

    public function updateAssurance(Request $request, Patiente $patiente)
{
    $request->validate([
        'est_assuree'            => 'boolean',
        'assurance_nom'          => 'nullable|string|max:150',
        'assurance_taux'         => 'nullable|numeric|min:0|max:100',
        'assurance_numero_police'=> 'nullable|string|max:100',
        'assurance_date_fin'     => 'nullable|date',
    ]);

    $patiente->update(['est_assuree' => $request->boolean('est_assuree')]);

    if ($request->boolean('est_assuree') && $request->input('assurance_nom')) {
        \App\Models\PatienteAssurance::updateOrCreate(
            ['patiente_id' => $patiente->id],
            [
                'assurance_id'    => null,
                'domaine'         => 'tout',
                'taux_couverture' => $request->input('assurance_taux') ?? 0,
                'numero_police'   => $request->input('assurance_numero_police'),
                'date_fin'        => $request->input('assurance_date_fin') ?: null,
                'actif'           => true,
                'notes'           => $request->input('assurance_nom'),
                'created_by'      => auth()->id(),
            ]
        );
    }

    return redirect()->route('patientes.show', $patiente)
        ->with('success', 'Assurance mise à jour.');
}
}
