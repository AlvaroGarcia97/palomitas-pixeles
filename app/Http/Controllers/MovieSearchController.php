<?php

namespace App\Http\Controllers;

use App\Services\TmdbService;
use Illuminate\Http\Request;

class MovieSearchController extends Controller
{
    // Laravel "inyecta" el servicio solo (inyección de dependencias):
    // no hacemos "new TmdbService()", lo pide el constructor y Laravel lo crea.
    public function __construct(protected TmdbService $tmdb)
    {
    }

    // GET /buscar -> busca películas si llega el parámetro ?q=
    public function index(Request $request)
    {
        $query  = trim((string) $request->query('q', ''));
        $movies = [];
        $error  = null;

        if ($query !== '') {
            try {
                $movies = $this->tmdb->searchMovies($query);
            } catch (\Throwable $e) {
                $error = 'No se pudo consultar TMDB. Inténtalo de nuevo.';
            }
        }

        return view('search', compact('query', 'movies', 'error'));
    }
}
