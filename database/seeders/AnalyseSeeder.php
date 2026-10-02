<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnalyseSeeder extends Seeder
{
    public function run(): void
    {
        $analyses = [

            // HÉMATOLOGIE
            ['categorie' => 'Hématologie', 'nom' => 'NFS', 'prix_standard' => 55000],
            ['categorie' => 'Hématologie', 'nom' => 'THb', 'prix_standard' => 25000],
            ['categorie' => 'Hématologie', 'nom' => 'TE', 'prix_standard' => 25000],
            ['categorie' => 'Hématologie', 'nom' => 'TC-TS', 'prix_standard' => 25000],
            ['categorie' => 'Hématologie', 'nom' => 'GB', 'prix_standard' => 25000],
            ['categorie' => 'Hématologie', 'nom' => 'GR', 'prix_standard' => 25000],
            ['categorie' => 'Hématologie', 'nom' => 'Plaquettes', 'prix_standard' => 25000],
            ['categorie' => 'Hématologie', 'nom' => 'Réticulocytes', 'prix_standard' => 50000],
            ['categorie' => 'Hématologie', 'nom' => 'Électrophorèse', 'prix_standard' => 55000],
            ['categorie' => 'Hématologie', 'nom' => 'Électrophorèse des Protéines', 'prix_standard' => 110000],

            // BIOCHIMIE SANG
            ['categorie' => 'Biochimie Sang', 'nom' => 'Glycémie', 'prix_standard' => 35000],
            ['categorie' => 'Biochimie Sang', 'nom' => 'Urée Sanguine', 'prix_standard' => 55000],
            ['categorie' => 'Biochimie Sang', 'nom' => 'Créatinine', 'prix_standard' => 55000],
            ['categorie' => 'Biochimie Sang', 'nom' => 'Acide Urique ou Uricémie', 'prix_standard' => 55000],
            ['categorie' => 'Biochimie Sang', 'nom' => 'Bilirubines', 'prix_standard' => 55000],
            ['categorie' => 'Biochimie Sang', 'nom' => 'Cholestérol Total', 'prix_standard' => 55000],
            ['categorie' => 'Biochimie Sang', 'nom' => 'Triglycérides', 'prix_standard' => 100000],
            ['categorie' => 'Biochimie Sang', 'nom' => 'Transaminases', 'prix_standard' => 110000],
            ['categorie' => 'Biochimie Sang', 'nom' => 'Gamma-GT', 'prix_standard' => 55000],
            ['categorie' => 'Biochimie Sang', 'nom' => 'Phosphatases Alcalines', 'prix_standard' => 55000],
            ['categorie' => 'Biochimie Sang', 'nom' => 'Protides Totaux', 'prix_standard' => 55000],
            ['categorie' => 'Biochimie Sang', 'nom' => 'Amylasémie', 'prix_standard' => 55000],
            ['categorie' => 'Biochimie Sang', 'nom' => 'GE', 'prix_standard' => 35000],

            // IONOGRAMME
            ['categorie' => 'Ionogramme', 'nom' => 'Magnésium', 'prix_standard' => 55000],
            ['categorie' => 'Ionogramme', 'nom' => 'Calcémie', 'prix_standard' => 55000],
            ['categorie' => 'Ionogramme', 'nom' => 'Potassium', 'prix_standard' => 55000],
            ['categorie' => 'Ionogramme', 'nom' => 'Sodium (Na+)', 'prix_standard' => 55000],

            // IMMUNO-SÉROLOGIE
            ['categorie' => 'Immuno-Sérologie', 'nom' => 'Groupage Sanguin (GSRH)', 'prix_standard' => 50000],
            ['categorie' => 'Immuno-Sérologie', 'nom' => 'BB-Test', 'prix_standard' => 25000],
            ['categorie' => 'Immuno-Sérologie', 'nom' => 'Widal', 'prix_standard' => 55000],
            ['categorie' => 'Immuno-Sérologie', 'nom' => 'AgHB', 'prix_standard' => 100000],
            ['categorie' => 'Immuno-Sérologie', 'nom' => 'SRV', 'prix_standard' => 55000],
            ['categorie' => 'Immuno-Sérologie', 'nom' => 'ASLO', 'prix_standard' => 55000],
            ['categorie' => 'Immuno-Sérologie', 'nom' => 'Facteur Rhumatoïde', 'prix_standard' => 55000],
            ['categorie' => 'Immuno-Sérologie', 'nom' => 'Toxo', 'prix_standard' => 300000],
            ['categorie' => 'Immuno-Sérologie', 'nom' => 'BW', 'prix_standard' => 55000],
            ['categorie' => 'Immuno-Sérologie', 'nom' => 'Chlamydiae', 'prix_standard' => 300000],

            // AUTRES EXAMENS
            ['categorie' => 'Autres', 'nom' => 'ECB Urines', 'prix_standard' => 55000],
            ['categorie' => 'Autres', 'nom' => 'Protéinurie', 'prix_standard' => 25000],
            ['categorie' => 'Autres', 'nom' => 'Selle', 'prix_standard' => 25000],
            ['categorie' => 'Autres', 'nom' => 'VS', 'prix_standard' => 55000],
            ['categorie' => 'Autres', 'nom' => 'Bandelettes Urinaires', 'prix_standard' => 160000],

            // HORMONES
            ['categorie' => 'Hormones', 'nom' => 'B-hCG', 'prix_standard' => 80000],
            ['categorie' => 'Hormones', 'nom' => 'TSH', 'prix_standard' => 80000],
            ['categorie' => 'Hormones', 'nom' => 'T3', 'prix_standard' => 80000],
            ['categorie' => 'Hormones', 'nom' => 'T4', 'prix_standard' => 80000],
            ['categorie' => 'Hormones', 'nom' => 'AMH', 'prix_standard' => 450000],
            ['categorie' => 'Hormones', 'nom' => 'FSH', 'prix_standard' => 80000],
            ['categorie' => 'Hormones', 'nom' => 'LH', 'prix_standard' => 80000],
            ['categorie' => 'Hormones', 'nom' => 'PRL', 'prix_standard' => 80000],
            ['categorie' => 'Hormones', 'nom' => 'Progestérone', 'prix_standard' => 80000],
            ['categorie' => 'Hormones', 'nom' => 'Testostérone', 'prix_standard' => 80000],
            ['categorie' => 'Hormones', 'nom' => 'Cortisol', 'prix_standard' => 80000],
        ];

        foreach ($analyses as $analyse) {
            DB::table('analyses')->insert([
                ...$analyse,
                'actif'      => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}