<?php

namespace App\Services;

use App\Models\RendezVous;
use App\Models\User;
use Carbon\Carbon;

class RendezVousService
{
    public function verifierChevauchement(User $medecin, Carbon $debut, int $dureeMinutes, ?int $exclureId = null): ?RendezVous
    {
        $fin = $debut->copy()->addMinutes($dureeMinutes);

        return RendezVous::where('medecin_id', $medecin->id)
            ->where('statut', '!=', 'annule')
            ->when($exclureId, fn($q) => $q->where('id', '!=', $exclureId))
            ->where(function ($query) use ($debut, $fin) {
                $query->whereBetween('date_heure_prevue', [$debut, $fin])
                      ->orWhere(function ($q) use ($debut) {
                          $q->where('date_heure_prevue', '<=', $debut)
                            ->whereRaw('DATE_ADD(date_heure_prevue, INTERVAL duree_minutes MINUTE) > ?', [$debut]);
                      });
            })
            ->first();
    }

    public function creerRendezVous(array $donnees, User $createur, bool $forcerMalgreConflit = false): RendezVous
    {
        $medecin = User::find($donnees['medecin_id']);
        $debut   = Carbon::parse($donnees['date_heure_prevue']);
        $duree   = $donnees['duree_minutes'] ?? 30;

        $conflit = $this->verifierChevauchement($medecin, $debut, $duree);

        if ($conflit && !$forcerMalgreConflit) {
            throw new \Exception("CONFLIT:{$conflit->id}:{$conflit->patiente->nom_complet}");
        }

        return RendezVous::create([
            ...$donnees,
            'created_by' => $createur->id,
            'statut'     => $donnees['statut'] ?? 'planifie',
        ]);
    }

    public function marquerAbsencesAutomatiques(): int
    {
        return RendezVous::where('statut', 'planifie')
            ->where('date_heure_prevue', '<', now()->subHours(2))
            ->update(['statut' => 'absent']);
    }
} 
