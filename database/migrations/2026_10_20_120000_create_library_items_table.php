<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Crea la tabla "library_items": las películas que cada usuario guarda.
    public function up(): void
    {
        Schema::create('library_items', function (Blueprint $table) {
            $table->id();

            // Usuario dueño de la película. Si se borra el usuario, se borra su club.
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Datos de la película, copiados de TMDB ("tmdb_id" es su id en TMDB).
            $table->unsignedInteger('tmdb_id');
            $table->string('title');
            $table->string('poster_path')->nullable();
            $table->string('release_date')->nullable();
            $table->text('overview')->nullable();

            // Estado dentro del club: "watchlist" (quiero verla) o "watched" (vista).
            $table->string('status')->default('watchlist');

            // Nota del 1 al 5 y reseña (ambas opcionales).
            $table->unsignedTinyInteger('rating')->nullable();
            $table->text('review')->nullable();

            // Fecha en que se marcó como vista.
            $table->timestamp('watched_at')->nullable();

            $table->timestamps();

            // Un usuario no puede tener la misma película dos veces.
            $table->unique(['user_id', 'tmdb_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('library_items');
    }
};
