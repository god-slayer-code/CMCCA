<?php

namespace App\Services;

use App\Models\Patiente;
use App\Models\User;
use Illuminate\Support\Collection;

class PatienteService
{
    public function chercherDoublons(string $nom, string $prenom, ?string $telephone, ?string $dateNaissance): Collection
    {
        return Patiente::where(function ($query) use ($nom, $prenom) {
            $query->whereRaw('SOUNDEX(nom) = SOUNDEX(?)', [$nom])
                  ->orWhereRaw('SOUNDEX(prenom) = SOUNDEX(?)', [$prenom]);
        })
        ->orWhere('telephone', $telephone)
        ->when($dateNaissance, fn($q) => $q->orWhere('date_naissance', $dateNaissance))
        ->limit(5)
        ->get();
    }

    public function creerPatiente(array $donnees, User $createur): Patiente
    {
        return Patiente::create([
            ...$donnees,
            'numero_dossier' => $this->genererNumeroDossier(),
            'created_by'     => $createur->id,
            'statut'         => 'active',
        ]);
    }

    private function genererNumeroDossier(): string
    {
        $annee   = now()->year;
        $dernier = Patiente::whereYear('created_at', $annee)->count() + 1;
        return sprintf('CLM-%d-%05d', $annee, $dernier);
    }
}
