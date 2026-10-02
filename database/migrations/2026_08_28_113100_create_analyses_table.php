<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analyses', function (Blueprint $table) {
            $table->id();
            $table->string('categorie');
            $table->string('nom');
            $table->decimal('prix_standard', 10, 2);
            $table->boolean('actif')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index('categorie');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analyses');
    }
};