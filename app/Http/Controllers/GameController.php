<?php

namespace App\Http\Controllers;

use App\Services\TmdbService;
use Illuminate\Http\Request;

class GameController extends Controller
{
    // Número de rondas por partida.
    private const ROUNDS = 10;

    public function __construct(protected TmdbService $tmdb)
    {
    }

    // GET /juego -> muestra la pregunta actual (o empieza partida).
    public function index()
    {
        if (! session()->has('game')) {
            $this->startGame();
        }

        $game = session('game');

        // Si la ronda ya estaba respondida (p.ej. volviste al menú "Juego"),
        // avanzamos a la siguiente reutilizando next().
        if ($game['answered'] ?? false) {
            return $this->next();
        }

        return view('game.index', compact('game'));
    }

    // POST /juego/respuesta -> comprueba la opción elegida.
    public function answer(Request $request)
    {
        $game = session('game');

        if (! $game) {
            return redirect()->route('game.index');
        }

        // Evita repetir la misma ronda pulsando "atrás" (no se pueden farmear puntos).
        if ($game['answered'] ?? false) {
            return redirect()->route('game.index');
        }

        $request->validate([
            'tmdb_id' => ['required', 'integer'],
        ]);

        $chosenId  = (int) $request->input('tmdb_id');
        $correctId = (int) $game['answer']['id'];
        $isCorrect = $chosenId === $correctId;

        if ($isCorrect) {
            $game['score']++;
        }

        $game['answered'] = true;
        session(['game' => $game]);

        return view('game.result', [
            'game'      => $game,
            'isCorrect' => $isCorrect,
            'chosenId'  => $chosenId,
        ]);
    }

    // GET /juego/siguiente -> avanza de ronda o termina la partida.
    public function next()
    {
        $game = session('game');

        if (! $game) {
            return redirect()->route('game.index');
        }

        // Si ya hemos jugado las 10 rondas, guardamos la puntuación.
        if ($game['round'] >= self::ROUNDS) {
            auth()->user()->gameScores()->create(['score' => $game['score']]);

            $finalScore = $game['score'];
            session()->forget('game');

            return view('game.finished', compact('finalScore'));
        }

        // Nueva ronda: subimos el número y generamos otra pregunta.
        $game['round']++;
        $round = $this->buildRound();

        $game['answer']   = $round['answer'];
        $game['options']  = $round['options'];
        $game['answered'] = false;

        session(['game' => $game]);

        return redirect()->route('game.index');
    }

    // GET /juego/reiniciar -> borra la partida y empieza otra.
    public function restart()
    {
        session()->forget('game');

        return redirect()->route('game.index');
    }

    // Crea una partida nueva en la sesión.
    private function startGame(): void
    {
        $round = $this->buildRound();

        session(['game' => [
            'round'    => 1,
            'score'    => 0,
            'answer'   => $round['answer'],
            'options'  => $round['options'],
            'answered' => false,
        ]]);
    }

    // Elige 4 películas populares: una es la respuesta y las otras, distractores.
    private function buildRound(): array
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $movies = $this->tmdb->popularMovies(random_int(1, 20));

            // Solo las que tienen carátula y título.
            $movies = array_values(array_filter(
                $movies,
                fn ($m) => ! empty($m['poster_path']) && ! empty($m['title']),
            ));

            // Quitamos duplicados por id.
            $movies = collect($movies)->unique('id')->values()->all();

            if (count($movies) >= 4) {
                shuffle($movies);

                // Nos quedamos con los datos que necesitamos (sesión más ligera).
                $options = array_map(fn ($m) => [
                    'id'           => $m['id'],
                    'title'        => $m['title'],
                    'poster_path'  => $m['poster_path'],
                    'release_date' => $m['release_date'] ?? '',
                ], array_slice($movies, 0, 4));

                $answer = $options[0];
                shuffle($options); // orden aleatorio en pantalla

                return ['answer' => $answer, 'options' => $options];
            }
        }

        abort(503, 'No hay suficientes películas ahora mismo. Inténtalo de nuevo.');
    }
}
