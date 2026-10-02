<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demandes_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patiente_id')->constrained('patientes');
            $table->foreignId('consultation_id')->nullable()->constrained('consultations')->nullOnDelete();
            $table->string('type_analyse');
            $table->enum('statut', ['en_attente', 'resultat_saisi', 'annule'])->default('en_attente');
            $table->foreignId('assignee_a')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('demande_par')->constrained('users');
            $table->string('motif_annulation')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demandes_analyses');
    }
};