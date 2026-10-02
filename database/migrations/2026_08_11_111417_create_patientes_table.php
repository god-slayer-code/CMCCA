<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patientes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_dossier')->unique();
            $table->string('nom');
            $table->string('prenom');
            $table->json('noms_precedents')->nullable();
            $table->date('date_naissance')->nullable();
            $table->unsignedSmallInteger('annee_naissance_approximative')->nullable();
            $table->boolean('date_naissance_approximative')->default(false);
            $table->string('adresse')->nullable();
            $table->string('telephone')->nullable();
            $table->string('contact_alternatif_nom')->nullable();
            $table->string('contact_alternatif_lien')->nullable();
            $table->enum('groupe_sanguin', ['A+','A-','B+','B-','AB+','AB-','O+','O-'])->nullable();
            $table->enum('statut', ['active','archivee','decedee'])->default('active');
            $table->date('date_deces')->nullable();
            $table->string('responsable_legal_nom')->nullable();
            $table->boolean('est_mineure')->default(false);
            $table->boolean('identite_incertaine')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            $table->index('nom');
            $table->index('prenom');
            $table->index('telephone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patientes');
    }
};