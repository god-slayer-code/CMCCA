<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedecinSeeder extends Seeder
{
    public function run(): void
    {
        $medecins = [
            ['nom' => 'Dr. À définir', 'specialite' => 'Gynéco-Obstétrique', 'actif' => true],
        ];

        foreach ($medecins as $medecin) {
            DB::table('medecins')->insert([
                ...$medecin,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}