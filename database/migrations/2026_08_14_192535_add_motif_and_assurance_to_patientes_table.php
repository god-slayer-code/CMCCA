<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patientes', function (Blueprint $table) {
            $table->foreignId('motif_visite_id')
                  ->nullable()
                  ->constrained('services')
                  ->nullOnDelete();
            $table->decimal('motif_visite_prix', 10, 2)->nullable();
            $table->boolean('est_assuree')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('patientes', function (Blueprint $table) {
            $table->dropForeign(['motif_visite_id']);
            $table->dropColumn(['motif_visite_id', 'motif_visite_prix', 'est_assuree']);
        });
    }
};