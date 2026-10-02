<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parametres_analyses', function (Blueprint $table) {
            $table->id();
            $table->string('categorie'); // NFS, Biochimie Sanguine, etc.
            $table->string('nom');       // GB, GR, THB, etc.
            $table->string('unite')->nullable(); // x 10³/µl, g/l, etc.
            $table->decimal('normale_min', 10, 4)->nullable();
            $table->decimal('normale_max', 10, 4)->nullable();
            $table->string('normale_texte')->nullable(); // Pour les normes complexes ex: "Homme 13-18 / Femme 12-16"
            $table->boolean('actif')->default(true);
            $table->integer('ordre')->default(0); // Pour l'ordre d'affichage
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parametres_analyses');
    }
};