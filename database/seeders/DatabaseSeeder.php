<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Rôles
        $this->call(RoleSeeder::class);

        // 2. Utilisateurs
        $this->call(UserSeeder::class);

        // 3. Services
        $this->call(ServiceSeeder::class);

        // 4. Analyses
        $this->call(AnalyseSeeder::class);

        // 5. Paramètres des analyses
        $this->call(ParametreAnalyseSeeder::class);

        // 6. Médecins
        $this->call(MedecinSeeder::class);

        // 7. Bilans
        $this->call(BilanSeeder::class);

        // 8. Configuration de la clinique
        $this->call(ConfigurationCliniqueSeeder::class);
    }
}