@extends('layouts.app')

@section('title', 'Resultado · '.config('app.name'))

@section('content')
    <div class="mx-auto max-w-xl text-center">
        <div class="mb-6 flex items-center justify-between text-sm text-neutral-400">
            <span>Ronda {{ $game['round'] }} de 10</span>
            <span>Puntos: {{ $game['score'] }}</span>
        </div>

        @if ($isCorrect)
            <div class="mb-6 rounded-lg border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-emerald-300">
                ¡Correcto!
            </div>
        @else
            <div class="mb-6 rounded-lg border border-red-500/40 bg-red-500/10 px-4 py-3 text-red-300">
                No era esa.
            </div>
        @endif

        <div class="mx-auto mb-4 w-64 overflow-hidden rounded-xl border border-white/10 bg-white/5">
            <img src="https://image.tmdb.org/t/p/w342{{ $game['answer']['poster_path'] }}"
                 alt="Carátula" class="h-96 w-full object-cover">
        </div>

        <p class="text-neutral-400">La respuesta era</p>
        <h2 class="text-xl font-semibold">{{ $game['answer']['title'] }}</h2>

        {{-- Si fallaste, mostramos qué opción habías marcado. --}}
        @if (! $isCorrect)
            @php($chosen = collect($game['options'])->firstWhere('id', $chosenId))
            @if ($chosen)
                <p class="mt-1 text-sm text-neutral-500">Elegiste: {{ $chosen['title'] }}</p>
            @endif
        @endif

        <a href="{{ route('game.next') }}"
           class="mt-8 inline-block rounded-full bg-amber-400 px-6 py-2 font-semibold text-black hover:bg-amber-300">
            {{ $game['round'] >= 10 ? 'Ver resultado' : 'Siguiente' }}
        </a>
    </div>
@endsection
