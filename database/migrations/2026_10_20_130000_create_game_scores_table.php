<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Guarda la puntuación de cada partida del juego.
    public function up(): void
    {
        Schema::create('game_scores', function (Blueprint $table) {
            $table->id();

            // Usuario que jugó. Si se borra el usuario, se borran sus partidas.
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Aciertos sobre 10.
            $table->unsignedTinyInteger('score');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_scores');
    }
};
