<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Patiente;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    // Liste des patientes du jour pour le médecin
    public function index()
    {
        $patientes = Patiente::actives()
            ->orderBy('nom')
            ->get();

        return view('consultations.index', compact('patientes'));
    }

    // Fiche patiente + sélection acte pour le médecin
  public function show(Patiente $patiente)
{
    $services = Service::where('actif', true)->orderBy('nom')->get();
    $consultations = $patiente->consultations()
        ->with('service', 'medecin')
        ->orderBy('created_at', 'desc')
        ->get();

    $resultatsAnalyses = \App\Models\DemandeAnalyse::where('patiente_id', $patiente->id)
        ->with('resultat')
        ->orderBy('created_at', 'desc')
        ->get();

    return view('consultations.show', compact('patiente', 'services', 'consultations', 'resultatsAnalyses'));
}

    // Créer une consultation (brouillon)
    public function store(Request $request)
    {
        $request->validate([
            'patiente_id' => 'required|exists:patientes,id',
            'service_id'  => 'required|exists:services,id',
        ]);

        $consultation = Consultation::create([
            'patiente_id' => $request->patiente_id,
            'medecin_id'  => auth()->id(),
            'service_id'  => $request->service_id,
            'motif'       => $request->motif,
            'diagnostic'  => $request->diagnostic,
            'notes'       => $request->notes,
            'poids'       => $request->poids,
            'tension'     => $request->tension,
            'temperature' => $request->temperature,
            'statut'      => 'brouillon',
            'created_by'  => auth()->id(),
        ]);

        return redirect()->route('consultations.show', $request->patiente_id)
            ->with('success', 'Consultation enregistrée.');
    }

    // Valider une consultation
    public function valider(Consultation $consultation)
    {
        if ($consultation->estValidee()) {
            return back()->with('error', 'Cette consultation est déjà validée.');
        }

        $consultation->update([
            'statut'          => 'validee',
            'date_validation' => now(),
        ]);

        return back()->with('success', 'Consultation validée.');
    }

    // Imprimer la fiche de consultation
    public function imprimer(Consultation $consultation)
    {
        return view('consultations.imprimer', compact('consultation'));
    }
}