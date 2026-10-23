<?php

namespace App\Http\Controllers;

class RouletteController extends Controller
{
    // GET /ruleta -> elige al azar una película de "Quiero ver".
    public function index()
    {
        $watchlist = auth()->user()->libraryItems()
            ->where('status', 'watchlist')
            ->get();

        // Si no hay nada que sortear, avisamos y volvemos al club.
        if ($watchlist->isEmpty()) {
            return redirect()->route('library.index')
                ->with('ok', 'Añade películas a "Quiero ver" para usar la ruleta.');
        }

        // random() devuelve un elemento al azar de la colección.
        $chosen = $watchlist->random();

        // Carátulas para la animación (solo las que tienen imagen).
        $posters = $watchlist
            ->whereNotNull('poster_path')
            ->map(fn ($item) => 'https://image.tmdb.org/t/p/w342'.$item->poster_path)
            ->values()
            ->all();

        $chosenPoster = $chosen->poster_path
            ? 'https://image.tmdb.org/t/p/w342'.$chosen->poster_path
            : null;

        return view('roulette.index', compact('chosen', 'posters', 'chosenPoster'));
    }
}
