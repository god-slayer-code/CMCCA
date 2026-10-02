<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParametreAnalyseSeeder extends Seeder
{
    public function run(): void
    {
        $parametres = [
            // NFS — Numération et Formule Sanguine
            ['categorie' => 'NFS', 'nom' => 'GB (Globules Blancs)',          'unite' => 'x 10³/µl',  'normale_min' => 4,    'normale_max' => 8.9,  'normale_texte' => '4 - 8,9 x 10³/µl',                          'ordre' => 1],
            ['categorie' => 'NFS', 'nom' => 'GR (Globules Rouges)',          'unite' => 'x 10⁶/µl',  'normale_min' => 4.5,  'normale_max' => 6,    'normale_texte' => '4,5 - 6 x 10⁶/µl',                          'ordre' => 2],
            ['categorie' => 'NFS', 'nom' => 'THB (Hémoglobine)',             'unite' => 'g/dl',       'normale_min' => 12,   'normale_max' => 18,   'normale_texte' => 'Homme 13-18 g/dl / Femme 12-16 g/dl',        'ordre' => 3],
            ['categorie' => 'NFS', 'nom' => 'Hte (Hématocrite)',             'unite' => '%',          'normale_min' => 35,   'normale_max' => 50,   'normale_texte' => 'Homme 40-50% / Femme 35-47%',                'ordre' => 4],
            ['categorie' => 'NFS', 'nom' => 'PN (Polynucléaires Neutrophiles)', 'unite' => '%',       'normale_min' => 45,   'normale_max' => 70,   'normale_texte' => '45 - 70 %',                                  'ordre' => 5],
            ['categorie' => 'NFS', 'nom' => 'PE (Polynucléaires Éosinophiles)', 'unite' => '%',       'normale_min' => 1,    'normale_max' => 5,    'normale_texte' => '1 - 5 %',                                    'ordre' => 6],
            ['categorie' => 'NFS', 'nom' => 'PB (Polynucléaires Basophiles)', 'unite' => '%',        'normale_min' => 0,    'normale_max' => 1,    'normale_texte' => '0 - 1 %',                                    'ordre' => 7],
            ['categorie' => 'NFS', 'nom' => 'L (Lymphocytes)',               'unite' => '%',          'normale_min' => 25.5, 'normale_max' => 40,   'normale_texte' => '25,5 - 40 %',                               'ordre' => 8],
            ['categorie' => 'NFS', 'nom' => 'M (Monocytes)',                 'unite' => '%',          'normale_min' => 7,    'normale_max' => 11,   'normale_texte' => '7 - 11 %',                                   'ordre' => 9],

            // Biochimie Sanguine
            ['categorie' => 'Biochimie Sanguine', 'nom' => 'Glycémie pp',   'unite' => 'g/l',        'normale_min' => 0.54, 'normale_max' => 1.2,  'normale_texte' => '3,6 - 6 mmol/L / 0,54 - 1,2 g/L',          'ordre' => 1],
            ['categorie' => 'Biochimie Sanguine', 'nom' => 'Glycémie à jeun', 'unite' => 'g/l',      'normale_min' => 0.70, 'normale_max' => 1.10, 'normale_texte' => '0,70 - 1,10 g/L',                           'ordre' => 2],
            ['categorie' => 'Biochimie Sanguine', 'nom' => 'Urée',          'unite' => 'g/l',        'normale_min' => 0.15, 'normale_max' => 0.45, 'normale_texte' => '0,15 - 0,45 g/L',                           'ordre' => 3],
            ['categorie' => 'Biochimie Sanguine', 'nom' => 'Créatinine',    'unite' => 'mg/l',       'normale_min' => 6,    'normale_max' => 13,   'normale_texte' => '6 - 13 mg/L',                               'ordre' => 4],
            ['categorie' => 'Biochimie Sanguine', 'nom' => 'Cholestérol Total', 'unite' => 'g/l',   'normale_min' => 1.5,  'normale_max' => 2.0,  'normale_texte' => '1,5 - 2,0 g/L',                             'ordre' => 5],
            ['categorie' => 'Biochimie Sanguine', 'nom' => 'Triglycérides', 'unite' => 'g/l',       'normale_min' => 0.5,  'normale_max' => 1.5,  'normale_texte' => '0,5 - 1,5 g/L',                             'ordre' => 6],
            ['categorie' => 'Biochimie Sanguine', 'nom' => 'Transaminases SGOT (ASAT)', 'unite' => 'UI/l', 'normale_min' => 5, 'normale_max' => 40, 'normale_texte' => '5 - 40 UI/L',                             'ordre' => 7],
            ['categorie' => 'Biochimie Sanguine', 'nom' => 'Transaminases SGPT (ALAT)', 'unite' => 'UI/l', 'normale_min' => 5, 'normale_max' => 40, 'normale_texte' => '5 - 40 UI/L',                             'ordre' => 8],

            // Inflammation
            ['categorie' => 'Inflammation', 'nom' => 'CRP (Protéine C Réactive)', 'unite' => 'mg/l', 'normale_min' => 0, 'normale_max' => 6, 'normale_texte' => '< 6 mg/L',                                       'ordre' => 1],
            ['categorie' => 'Inflammation', 'nom' => 'VS (Vitesse de Sédimentation)', 'unite' => 'mm/h', 'normale_min' => 0, 'normale_max' => 20, 'normale_texte' => '< 20 mm/h',                                 'ordre' => 2],

            // Bilan d'Hémostase
            ['categorie' => "Bilan d'Hémostase", 'nom' => 'TP (Taux de Prothrombine)', 'unite' => '%', 'normale_min' => 70, 'normale_max' => 100, 'normale_texte' => '70 - 100 %',                                'ordre' => 1],
            ['categorie' => "Bilan d'Hémostase", 'nom' => 'TCA (Temps de Céphaline Activée)', 'unite' => 'sec', 'normale_min' => 28, 'normale_max' => 40, 'normale_texte' => '28 - 40 sec',                       'ordre' => 2],
            ['categorie' => "Bilan d'Hémostase", 'nom' => 'Plaquettes',     'unite' => 'x 10³/µl',  'normale_min' => 150,  'normale_max' => 400,  'normale_texte' => '150 - 400 x 10³/µl',                        'ordre' => 3],

            // Sérologie
            ['categorie' => 'Sérologie', 'nom' => 'AgHBs (Hépatite B)',     'unite' => null,         'normale_min' => null, 'normale_max' => null, 'normale_texte' => 'Négatif',                                    'ordre' => 1],
            ['categorie' => 'Sérologie', 'nom' => 'Anti-HCV (Hépatite C)', 'unite' => null,          'normale_min' => null, 'normale_max' => null, 'normale_texte' => 'Négatif',                                    'ordre' => 2],
            ['categorie' => 'Sérologie', 'nom' => 'VIH 1 et 2',            'unite' => null,          'normale_min' => null, 'normale_max' => null, 'normale_texte' => 'Négatif',                                    'ordre' => 3],
            ['categorie' => 'Sérologie', 'nom' => 'TPHA/VDRL (Syphilis)', 'unite' => null,           'normale_min' => null, 'normale_max' => null, 'normale_texte' => 'Négatif',                                    'ordre' => 4],
            ['categorie' => 'Sérologie', 'nom' => 'Toxoplasmose IgG/IgM',  'unite' => null,          'normale_min' => null, 'normale_max' => null, 'normale_texte' => 'Négatif',                                    'ordre' => 5],

            // Groupe Sanguin
            ['categorie' => 'Groupe Sanguin', 'nom' => 'Groupe ABO',        'unite' => null,         'normale_min' => null, 'normale_max' => null, 'normale_texte' => 'A / B / AB / O',                             'ordre' => 1],
            ['categorie' => 'Groupe Sanguin', 'nom' => 'Rhésus',            'unite' => null,         'normale_min' => null, 'normale_max' => null, 'normale_texte' => 'Positif / Négatif',                          'ordre' => 2],
        ];

        foreach ($parametres as $parametre) {
            DB::table('parametres_analyses')->insert([
                ...$parametre,
                'actif' => true,
            ]);
        }
    }
}