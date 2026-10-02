<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patiente_id')->constrained('patientes');
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->string('numero_facture')->unique();
            $table->decimal('montant_service', 10, 2);
            $table->decimal('montant_ajuste', 10, 2)->nullable();
            $table->decimal('montant_final', 10, 2);
            $table->foreignId('ajuste_par')->nullable()->constrained('users')->nullOnDelete();
            $table->string('motif_ajustement')->nullable();
            $table->enum('statut', [
                'en_attente_validation',
                'partiellement_payee',
                'payee',
                'annulee'
            ])->default('en_attente_validation');
            $table->foreignId('enregistree_par')->constrained('users');
            $table->foreignId('validee_par')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('date_validation')->nullable();
            $table->string('motif_annulation')->nullable();
            $table->foreignId('annule_par')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};