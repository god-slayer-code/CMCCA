<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuration_clinique', function (Blueprint $table) {
            $table->id();
            $table->string('nom_clinique');
            $table->string('slogan')->nullable();
            $table->string('logo')->nullable();
            $table->string('telephone_1');
            $table->string('telephone_2')->nullable();
            $table->string('email')->nullable();
            $table->string('adresse');
            $table->string('ville')->default('Conakry');
            $table->string('pays')->default('Guinée');
            $table->string('bp')->nullable();
            $table->string('compte_bancaire')->nullable();
            $table->string('swift')->nullable();
            $table->string('pied_de_page')->nullable();
            $table->foreignId('modifie_par')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuration_clinique');
    }
};