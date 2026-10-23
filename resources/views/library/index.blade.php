@extends('layouts.app')

@section('title', 'Mi club · '.config('app.name'))

@section('content')
    <h1 class="mb-6 text-2xl font-bold">Mi club</h1>

    @if ($items->isEmpty())
        <p class="text-neutral-400">
            Aún no tienes películas.
            <a href="{{ route('movies.search') }}" class="text-amber-400 hover:underline">Busca una</a> para empezar.
        </p>
    @endif

    {{-- Separamos las películas por estado con dos listas. --}}
    @php
        $secciones = [
            'Quiero ver' => $items->where('status', 'watchlist'),
            'Vistas'     => $items->where('status', 'watched'),
        ];
    @endphp

    @foreach ($secciones as $titulo => $lista)
        @if ($lista->isNotEmpty())
            <h2 class="mb-4 mt-8 text-lg font-semibold text-neutral-200">{{ $titulo }} ({{ $lista->count() }})</h2>

            <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                @foreach ($lista as $item)
                    <a href="{{ route('library.show', $item->tmdb_id) }}"
                       class="group overflow-hidden rounded-xl border border-white/10 bg-white/5">
                        @if ($item->poster_path)
                            <img src="https://image.tmdb.org/t/p/w342{{ $item->poster_path }}"
                                 alt="{{ $item->title }}" class="h-72 w-full object-cover">
                        @else
                            <div class="flex h-72 items-center justify-center bg-white/5 text-sm text-neutral-500">Sin carátula</div>
                        @endif

                        <div class="p-3">
                            <h3 class="font-semibold leading-tight group-hover:text-amber-300">{{ $item->title }}</h3>
                            <p class="mt-1 text-xs text-neutral-400">
                                {{ substr($item->release_date ?? '', 0, 4) ?: '—' }}
                                @if ($item->rating) · {{ $item->rating }}/5 @endif
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    @endforeach
@endsection
