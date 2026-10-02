<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patiente_id')->constrained('patientes');
            $table->foreignId('medecin_id')->constrained('users');
            $table->foreignId('rendez_vous_id')->nullable()->constrained('rendez_vous')->nullOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->text('motif')->nullable();
            $table->text('diagnostic')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('poids', 5, 2)->nullable();
            $table->string('tension')->nullable();
            $table->decimal('temperature', 4, 1)->nullable();
            $table->enum('statut', ['brouillon', 'validee'])->default('brouillon');
            $table->timestamp('date_validation')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};