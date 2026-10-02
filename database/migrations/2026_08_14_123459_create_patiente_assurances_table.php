<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patiente_assurances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patiente_id')->constrained('patientes')->cascadeOnDelete();
            $table->foreignId('assurance_id')->constrained('assurances');
            $table->enum('domaine', [
                'tout',
                'consultation',
                'chirurgie',
                'laboratoire',
                'maternite',
                'autre'
            ])->default('tout');
            $table->decimal('taux_couverture', 5, 2); // ex: 80.00 pour 80%
            $table->decimal('plafond_montant', 10, 2)->nullable(); // limite en GNF
            $table->integer('plafond_visites')->nullable(); // limite en nombre de visites
            $table->integer('visites_utilisees')->default(0);
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->string('numero_police')->nullable(); // numéro de contrat
            $table->boolean('actif')->default(true);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patiente_assurances');
    }
};