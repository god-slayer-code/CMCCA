<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resultats_parametres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_analyse_id')->constrained('demandes_analyses');
            $table->foreignId('parametre_id')->constrained('parametres_analyses');
            $table->string('valeur')->nullable(); // Peut être vide si pas encore analysé
            $table->boolean('hors_norme')->default(false);
            $table->foreignId('saisi_par')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('saisi_le')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resultats_parametres');
    }
};