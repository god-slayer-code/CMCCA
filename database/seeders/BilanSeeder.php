<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\Analyse;

class BilanSeeder extends Seeder
{
    public function run(): void
    {
        $bilans = [

            'Bilan Traitement' => [
                'prix' => 1360000,
                'examens' => ['TOXO', 'Rubéole', 'Chlamydiae', 'BW', 'AgHB', 'Facteur Rhumatoïde', 'SRV', 'Glycémie', 'TE', 'NFS', 'ECB Urines', 'Groupage Sanguin (GSRH)'],
            ],

            'Bilan CPN' => [
                'prix' => 850000,
                'examens' => ['TOXO', 'GE', 'THb', 'GR', 'AgHB', 'Facteur Rhumatoïde', 'SRV', 'Glycémie', 'Widal', 'Protéinurie', 'ECB Urines', 'TE', 'Selle'],
            ],

            '2ème Bilan de la Grossesse' => [
                'prix' => 255000,
                'examens' => ['NFS', 'Glycémie', 'Bandelettes Urinaires'],
            ],

            "Bilan d'accouchement" => [
                'prix' => 635000,
                'examens' => ['NFS', 'TS', 'TC-TS', 'Urée Sanguine', 'Créatinine', 'VS', 'Glycémie', 'Plaquettes', 'Transaminases', 'Bandelettes Urinaires'],
            ],
        ];

        foreach ($bilans as $nomBilan => $donnees) {
            $service = Service::firstOrCreate(
                ['nom' => $nomBilan],
                ['prix_standard' => $donnees['prix'], 'actif' => true]
            );

            foreach ($donnees['examens'] as $nomExamen) {
                $analyse = Analyse::where('nom', $nomExamen)->first();

                if ($analyse) {
                    $service->examens()->syncWithoutDetaching([$analyse->id => ['obligatoire' => true]]);
                } else {
                    $this->command->warn("Analyse introuvable pour '{$nomBilan}' : {$nomExamen}");
                }
            }
        }
    }
}