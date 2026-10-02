<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rendez_vous', function (Blueprint $table) {
            $table->dropColumn('motif');
        });

        Schema::table('rendez_vous', function (Blueprint $table) {
            $table->foreignId('motif_id')
                  ->nullable()
                  ->after('medecin_id')
                  ->constrained('services')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('rendez_vous', function (Blueprint $table) {
            $table->dropForeign(['motif_id']);
            $table->dropColumn('motif_id');
        });

        Schema::table('rendez_vous', function (Blueprint $table) {
            $table->string('motif')->nullable();
        });
    }
};