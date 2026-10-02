<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resultats_analyses', function (Blueprint $table) {
            $table->foreignId('demande_analyse_id')->after('id')->constrained('demandes_analyses');
            $table->string('resultat')->after('demande_analyse_id');
            $table->boolean('valeur_hors_norme')->default(false)->after('resultat');
            $table->foreignId('saisi_par')->after('valeur_hors_norme')->constrained('users');
        });
    }

    public function down(): void
    {
        Schema::table('resultats_analyses', function (Blueprint $table) {
            $table->dropForeign(['demande_analyse_id']);
            $table->dropForeign(['saisi_par']);
            $table->dropColumn(['demande_analyse_id', 'resultat', 'valeur_hors_norme', 'saisi_par']);
        });
    }
};