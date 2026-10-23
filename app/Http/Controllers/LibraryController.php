<?php

namespace App\Http\Controllers;

use App\Services\TmdbService;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    // Inyectamos TMDB para pedir los datos de la ficha de una película.
    public function __construct(protected TmdbService $tmdb)
    {
    }

    // GET /club -> lista las películas del usuario (separadas por estado en la vista).
    public function index()
    {
        $items = auth()->user()->libraryItems()->latest()->get();

        return view('library.index', compact('items'));
    }

    // POST /club -> añade una película (llega desde el buscador).
    public function store(Request $request)
    {
        $data = $request->validate([
            'tmdb_id'      => ['required', 'integer'],
            'title'        => ['required', 'string', 'max:255'],
            'poster_path'  => ['nullable', 'string', 'max:255'],
            'release_date' => ['nullable', 'string', 'max:20'],
            'overview'     => ['nullable', 'string'],
        ]);

        // updateOrCreate: si ya existía (mismo tmdb_id) lo actualiza; si no, lo crea.
        // Evita duplicados aunque pulses "Añadir" dos veces.
        auth()->user()->libraryItems()->updateOrCreate(
            ['tmdb_id' => $data['tmdb_id']],
            $data,
        );

        return back()->with('ok', 'Película añadida a tu club.');
    }

    // GET /club/{tmdbId} -> ficha de la película + formulario para editarla.
    public function show(int $tmdbId)
    {
        // Datos "frescos" desde TMDB para la ficha.
        $movie = $this->tmdb->movieDetail($tmdbId);

        // Lo que este usuario tenga guardado de esa película (o null si no la tiene).
        $item = auth()->user()->libraryItems()->where('tmdb_id', $tmdbId)->first();

        return view('library.show', compact('movie', 'item'));
    }

    // PUT /club/{tmdbId} -> guarda estado, nota y reseña.
    public function update(Request $request, int $tmdbId)
    {
        // firstOrFail: si el usuario no tiene esa película, responde 404.
        $item = auth()->user()->libraryItems()->where('tmdb_id', $tmdbId)->firstOrFail();

        $data = $request->validate([
            'status' => ['required', 'in:watchlist,watched'],
            'rating' => ['nullable', 'integer', 'between:1,5'],
            'review' => ['nullable', 'string', 'max:1000'],
        ]);

        // Al pasar a "vista" por primera vez, guardámonos la fecha.
        if ($data['status'] === 'watched' && ! $item->watched_at) {
            $data['watched_at'] = now();
        }

        $item->update($data);

        return back()->with('ok', 'Cambios guardados.');
    }

    // DELETE /club/{tmdbId} -> quita la película del club.
    public function destroy(int $tmdbId)
    {
        auth()->user()->libraryItems()->where('tmdb_id', $tmdbId)->delete();

        return redirect()->route('library.index')->with('ok', 'Película eliminada de tu club.');
    }
}
