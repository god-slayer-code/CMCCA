<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('demandes_analyses', function (Blueprint $table) {
            $table->boolean('paiement_valide')->default(false)->after('motif_annulation');
            $table->timestamp('date_paiement')->nullable()->after('paiement_valide');
            $table->foreignId('valide_par')->nullable()->constrained('users')->nullOnDelete()->after('date_paiement');
            $table->decimal('montant_paye', 10, 2)->nullable()->after('valide_par');
        });
    }

    public function down(): void
    {
        Schema::table('demandes_analyses', function (Blueprint $table) {
            $table->dropForeign(['valide_par']);
            $table->dropColumn(['paiement_valide', 'date_paiement', 'valide_par', 'montant_paye']);
        });
    }
};