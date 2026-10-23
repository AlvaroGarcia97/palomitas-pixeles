<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

// Servicio que centraliza las llamadas a la API de TMDB.
// Así los controladores no hablan con TMDB directamente: solo piden datos aquí.
class TmdbService
{
    // URL base de la API (versión 3).
    protected string $baseUrl = 'https://api.themoviedb.org/3';

    // Busca películas por texto y devuelve la lista de resultados.
    public function searchMovies(string $query): array
    {
        return $this->get('/search/movie', ['query' => $query])['results'] ?? [];
    }

    // Devuelve la ficha completa de una película por su id de TMDB.
    public function movieDetail(int $id): array
    {
        return $this->get('/movie/'.$id);
    }

    // Devuelve una página de películas populares (para el juego).
    public function popularMovies(int $page = 1): array
    {
        return $this->get('/movie/popular', ['page' => $page])['results'] ?? [];
    }

    // GET genérico: añade la api_key y el idioma a cualquier petición.
    protected function get(string $path, array $params = []): array
    {
        $response = Http::get($this->baseUrl.$path, array_merge([
            'api_key'  => config('services.tmdb.key'),
            'language' => 'es-ES',
        ], $params));

        // Si TMDB responde con error (4xx/5xx), lanza una excepción.
        $response->throw();

        // Convertimos la respuesta JSON en un array de PHP.
        return $response->json();
    }
}
