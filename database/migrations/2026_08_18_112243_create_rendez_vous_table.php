<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rendez_vous', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patiente_id')->constrained('patientes');
            $table->foreignId('medecin_id')->constrained('users');
            $table->datetime('date_heure_prevue');
            $table->datetime('date_heure_reelle')->nullable();
            $table->integer('duree_minutes')->default(30);
            $table->enum('statut', [
                'planifie',
                'confirme',
                'en_cours',
                'termine',
                'annule',
                'absent',
                'urgence'
            ])->default('planifie');
            $table->string('motif_annulation')->nullable();
            $table->unsignedBigInteger('reprogramme_depuis_id')->nullable();
            $table->foreign('reprogramme_depuis_id')->references('id')->on('rendez_vous')->nullOnDelete();
            $table->boolean('confirme')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
            $table->index('date_heure_prevue');
            $table->index('medecin_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rendez_vous');
    }
};