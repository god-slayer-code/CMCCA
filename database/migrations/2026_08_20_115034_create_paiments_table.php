<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facture_id')->constrained('factures');
            $table->decimal('montant', 10, 2);
            $table->enum('mode_paiement', [
                'especes',
                'mobile_money',
                'carte',
                'autre'
            ])->default('especes');
            $table->string('reference')->nullable();
            $table->foreignId('enregistre_par')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};