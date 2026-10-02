<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journal_audit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('action');
            $table->string('table_concernee');
            $table->unsignedBigInteger('enregistrement_id')->nullable();
            $table->json('details_json')->nullable();
            $table->string('ip_adresse')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['table_concernee', 'enregistrement_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_audit');
    }
};
