@extends('layouts.app')

@section('title', 'Juego · '.config('app.name'))

@section('content')
    <div class="mx-auto max-w-xl">
        <div class="mb-6 flex items-center justify-between text-sm text-neutral-400">
            <span>Ronda {{ $game['round'] }} de 10</span>
            <span>Puntos: {{ $game['score'] }}</span>
        </div>

        <h1 class="mb-6 text-2xl font-bold">¿Qué película es?</h1>

        <div class="mx-auto mb-8 w-64 overflow-hidden rounded-xl border border-white/10 bg-white/5">
            <img src="https://image.tmdb.org/t/p/w342{{ $game['answer']['poster_path'] }}"
                 alt="Carátula" class="h-96 w-full object-cover">
        </div>

        {{-- Cada opción es un botón que envía su tmdb_id al controlador. --}}
        <form method="POST" action="{{ route('game.answer') }}" class="grid gap-3">
            @csrf

            @foreach ($game['options'] as $option)
                <button type="submit" name="tmdb_id" value="{{ $option['id'] }}"
                        class="rounded-lg border border-white/10 bg-white/5 px-4 py-3 text-left font-medium transition hover:border-amber-400 hover:bg-amber-400/10">
                    {{ $option['title'] }}
                </button>
            @endforeach
        </form>
    </div>
@endsection
