<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['nom' => 'Consultation Gynéco-Obstétrique', 'prix_standard' => 80000],
            ['nom' => 'Échographie', 'prix_standard' => 130000],
            ['nom' => 'Aspiration Manuelle Utérine (AMIU)', 'prix_standard' => 1000000],
            ['nom' => 'Ablation Polype', 'prix_standard' => 500000],
            ['nom' => 'Grossesse Extra Utérine (GEU)', 'prix_standard' => 8000000],
            ['nom' => 'Cerclage', 'prix_standard' => 2000000],
            ['nom' => 'Accouchement Normal', 'prix_standard' => 1300000],
            ['nom' => 'Accouchement Normal Gémellaire', 'prix_standard' => 2000000],
            ['nom' => 'Accouchement/Mort Née', 'prix_standard' => 2000000],
            ['nom' => 'Césarienne Anesthésie Général', 'prix_standard' => 6000000],
            ['nom' => 'Césarienne Rachi Anesthésie', 'prix_standard' => 8000000],
            ['nom' => 'Hystérectomie', 'prix_standard' => 10000000],
            ['nom' => 'Myomectomie + Produits Pré-post-opératoire', 'prix_standard' => 9000000],
            ['nom' => 'Kystectomie + Produits Pré-post-opératoire', 'prix_standard' => 9000000],
            ['nom' => 'Péritonite + Produits Pré-post-opératoire', 'prix_standard' => 10000000],
            ['nom' => 'Appendicite', 'prix_standard' => 2500000],
            ['nom' => 'Circoncision', 'prix_standard' => 500000],
            ['nom' => 'Hernie Inguinale', 'prix_standard' => 2000000],
            ['nom' => 'Hernie Inguinal Enfant', 'prix_standard' => 2000000],
            ['nom' => 'Hernie Bilatéral', 'prix_standard' => 3000000],
            ['nom' => 'Hernie Inguino-Scrotale', 'prix_standard' => 3000000],
            ['nom' => 'Hydrocèle', 'prix_standard' => 2000000],
            ['nom' => 'Hernie Ombilicale', 'prix_standard' => 1500000],
            ['nom' => 'Bartholinite', 'prix_standard' => 1000000],
            ['nom' => 'Périnéorraphie', 'prix_standard' => 3000000],
        ];

        foreach ($services as $service) {
            DB::table('services')->insert([
                ...$service,
                'actif' => true,
            ]);
        }
    }
}