<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    // GET /dashboard -> resumen del club y del juego del usuario.
    public function index()
    {
        $user = auth()->user();

        // --- Datos del club ---
        // Cada consulta se lanza por separado para no arrastrar filtros entre ellas.
        $total     = $user->libraryItems()->count();
        $watchlist = $user->libraryItems()->where('status', 'watchlist')->count();
        $watched   = $user->libraryItems()->where('status', 'watched')->count();

        // avg('rating') devuelve null si no hay notas.
        $avgRating = $user->libraryItems()->whereNotNull('rating')->avg('rating');
        $avgRating = $avgRating ? round($avgRating, 1) : null;

        // --- Datos del juego ---
        $gamesPlayed = $user->gameScores()->count();
        $bestScore   = $user->gameScores()->max('score');

        $avgScore = $user->gameScores()->avg('score');
        $avgScore = $avgScore ? round($avgScore, 1) : null;

        // --- Actividad reciente ---
        $recentAdded = $user->libraryItems()->latest()->take(3)->get();

        $recentWatched = $user->libraryItems()
            ->where('status', 'watched')
            ->latest('watched_at')
            ->take(3)
            ->get();

        return view('dashboard', compact(
            'total', 'watchlist', 'watched', 'avgRating',
            'gamesPlayed', 'bestScore', 'avgScore',
            'recentAdded', 'recentWatched',
        ));
    }
}
