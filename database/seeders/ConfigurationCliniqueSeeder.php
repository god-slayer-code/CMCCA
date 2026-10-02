<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigurationCliniqueSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('configuration_clinique')->insertOrIgnore([
            'nom_clinique'    => "Clinique Médico-Chirurgicale de la Cité de l'Air",
            'slogan'          => 'CMCCA',
            'telephone_1'     => '657 58 38 45',
            'telephone_2'     => '622 25 25 76',
            'email'           => 'mimellaud@yahoo.fr',
            'adresse'         => "Sis à Gbessia Cité de l'Air",
            'ville'           => 'Conakry',
            'pays'            => 'Guinée',
            'bp'              => '1601',
            'compte_bancaire' => '10002496210025',
            'swift'           => 'ECOGNCN',
            'pied_de_page'    => 'Merci de votre confiance — CMCCA Conakry',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);
    }
}