<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rendez_vous', function (Blueprint $table) {
            $table->dropForeign(['medecin_id']);
        });

        Schema::table('rendez_vous', function (Blueprint $table) {
            $table->foreign('medecin_id')->references('id')->on('medecins');
            $table->string('motif')->nullable()->after('medecin_id');
        });
    }

    public function down(): void
    {
        Schema::table('rendez_vous', function (Blueprint $table) {
            $table->dropForeign(['medecin_id']);
            $table->dropColumn('motif');
        });

        Schema::table('rendez_vous', function (Blueprint $table) {
            $table->foreign('medecin_id')->references('id')->on('users');
        });
    }
};