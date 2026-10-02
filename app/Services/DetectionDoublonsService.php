<?php

namespace App\Services;

require_once app_path('Helpers/normalisation.php');

use App\Models\Patiente;
use Illuminate\Support\Collection;

class DetectionDoublonsService
{
    private const SEUIL_TOLERANCE = 2;

    public function rechercherDoublonsPotentiels(string $nom, string $prenom): Collection
    {
        $nomNormalise = normaliser($nom);
        $prenomNormalise = normaliser($prenom);

        return Patiente::all()->filter(function ($candidat) use ($nomNormalise, $prenomNormalise) {
            $distanceNom = levenshtein($nomNormalise, normaliser($candidat->nom));
            $distancePrenom = levenshtein($prenomNormalise, normaliser($candidat->prenom));

            return $distanceNom <= self::SEUIL_TOLERANCE
                && $distancePrenom <= self::SEUIL_TOLERANCE;
        });
    }
} 
