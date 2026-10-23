@extends('layouts.app')

@section('title', 'Mi resumen · '.config('app.name'))

@section('content')
    {{-- Cabecera con saludo y accesos rápidos --}}
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold">Hola, {{ auth()->user()->name }}</h1>
            <p class="text-neutral-400">Este es tu resumen cinéfilo.</p>
        </div>

        <div class="flex gap-2 text-sm">
            <a href="{{ route('movies.search') }}" class="rounded-full border border-white/15 px-4 py-2 font-semibold hover:bg-white/5">Buscar</a>
            <a href="{{ route('roulette.index') }}" class="rounded-full border border-white/15 px-4 py-2 font-semibold hover:bg-white/5">Ruleta</a>
            <a href="{{ route('game.index') }}" class="rounded-full bg-amber-400 px-4 py-2 font-semibold text-black hover:bg-amber-300">Jugar</a>
        </div>
    </div>

    {{-- Tu club --}}
    <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-neutral-500">Tu club</h2>
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
        @foreach ([
            ['Total', $total],
            ['Quiero ver', $watchlist],
            ['Vistas', $watched],
            ['Nota media', $avgRating ?? '—'],
        ] as $card)
            <div class="rounded-xl border border-white/10 bg-white/5 p-4">
                <p class="text-xs uppercase tracking-wide text-neutral-500">{{ $card[0] }}</p>
                <p class="mt-1 text-3xl font-bold">{{ $card[1] }}</p>
            </div>
        @endforeach
    </div>

    {{-- El juego --}}
    <h2 class="mb-3 mt-8 text-sm font-semibold uppercase tracking-wide text-neutral-500">El juego</h2>
    <div class="grid grid-cols-3 gap-4">
        @foreach ([
            ['Partidas', $gamesPlayed],
            ['Mejor', $bestScore ?? '—'],
            ['Media', $avgScore ?? '—'],
        ] as $card)
            <div class="rounded-xl border border-white/10 bg-white/5 p-4">
                <p class="text-xs uppercase tracking-wide text-neutral-500">{{ $card[0] }}</p>
                <p class="mt-1 text-3xl font-bold">{{ $card[1] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Actividad reciente --}}
    <div class="mt-8 grid gap-8 md:grid-cols-2">
        <div>
            <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-neutral-500">Añadidas hace poco</h2>

            @forelse ($recentAdded as $item)
                <a href="{{ route('library.show', $item->tmdb_id) }}"
                   class="mb-2 flex items-center gap-3 rounded-xl border border-white/10 bg-white/5 p-2 hover:border-amber-400/40">
                    @if ($item->poster_path)
                        <img src="https://image.tmdb.org/t/p/w92{{ $item->poster_path }}" alt="{{ $item->title }}" class="h-16 w-12 rounded object-cover">
                    @else
                        <div class="flex h-16 w-12 items-center justify-center rounded bg-white/5 text-xs text-neutral-500">N/A</div>
                    @endif
                    <div>
                        <p class="font-medium">{{ $item->title }}</p>
                        <p class="text-xs text-neutral-500">{{ $item->status === 'watched' ? 'Vista' : 'Quiero verla' }}</p>
                    </div>
                </a>
            @empty
                <p class="text-sm text-neutral-500">Todavía no has añadido nada.</p>
            @endforelse
        </div>

        <div>
            <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-neutral-500">Vistas hace poco</h2>

            @forelse ($recentWatched as $item)
                <a href="{{ route('library.show', $item->tmdb_id) }}"
                   class="mb-2 flex items-center gap-3 rounded-xl border border-white/10 bg-white/5 p-2 hover:border-amber-400/40">
                    @if ($item->poster_path)
                        <img src="https://image.tmdb.org/t/p/w92{{ $item->poster_path }}" alt="{{ $item->title }}" class="h-16 w-12 rounded object-cover">
                    @else
                        <div class="flex h-16 w-12 items-center justify-center rounded bg-white/5 text-xs text-neutral-500">N/A</div>
                    @endif
                    <div>
                        <p class="font-medium">{{ $item->title }}</p>
                        <p class="text-xs text-neutral-500">
                            {{ $item->watched_at?->diffForHumans() }}
                            @if ($item->rating) · {{ $item->rating }}/5 @endif
                        </p>
                    </div>
                </a>
            @empty
                <p class="text-sm text-neutral-500">Aún no has marcado ninguna como vista.</p>
            @endforelse
        </div>
    </div>
@endsection
