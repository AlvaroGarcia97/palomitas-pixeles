@extends('layouts.app')

@section('title', 'Buscar · '.config('app.name'))

@section('content')
    <h1 class="mb-6 text-2xl font-bold">Buscar películas</h1>

    {{-- method="GET": el texto viaja en la URL (?q=...), ideal para búsquedas. --}}
    <form method="GET" action="{{ route('movies.search') }}" class="mb-8 flex gap-2">
        <input type="text" name="q" value="{{ $query }}" placeholder="Título de la película..."
               class="flex-1 rounded-lg border border-white/10 bg-white/5 px-4 py-2 outline-none focus:border-amber-400">
        <button type="submit" class="rounded-lg bg-amber-400 px-5 font-semibold text-black hover:bg-amber-300">
            Buscar
        </button>
    </form>

    @if ($error)
        <div class="mb-6 rounded-lg border border-red-500/40 bg-red-500/10 px-4 py-3 text-red-300">
            {{ $error }}
        </div>
    @endif

    @if ($query !== '' && empty($movies) && ! $error)
        <p class="text-neutral-400">No se han encontrado resultados para "{{ $query }}".</p>
    @endif

    <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
        @foreach ($movies as $movie)
            <article class="overflow-hidden rounded-xl border border-white/10 bg-white/5">
                {{-- TMDB guarda solo la ruta de la carátula; la imagen completa está en image.tmdb.org. --}}
                @if (! empty($movie['poster_path']))
                    <img src="https://image.tmdb.org/t/p/w342{{ $movie['poster_path'] }}"
                         alt="{{ $movie['title'] }}" class="h-72 w-full object-cover">
                @else
                    <div class="flex h-72 items-center justify-center bg-white/5 text-sm text-neutral-500">
                        Sin carátula
                    </div>
                @endif

                <div class="p-3">
                    {{-- El título lleva a la ficha de la película. --}}
                    <a href="{{ route('library.show', $movie['id']) }}" class="font-semibold leading-tight hover:text-amber-300">
                        {{ $movie['title'] }}
                    </a>
                    <p class="mt-1 text-xs text-neutral-400">
                        {{ substr($movie['release_date'] ?? '', 0, 4) ?: '—' }}
                        · {{ number_format($movie['vote_average'] ?? 0, 1) }}/10
                    </p>

                    {{-- Formulario para guardar la película en el club de una vez. --}}
                    <form method="POST" action="{{ route('library.store') }}" class="mt-3">
                        @csrf
                        <input type="hidden" name="tmdb_id" value="{{ $movie['id'] }}">
                        <input type="hidden" name="title" value="{{ $movie['title'] }}">
                        <input type="hidden" name="poster_path" value="{{ $movie['poster_path'] }}">
                        <input type="hidden" name="release_date" value="{{ $movie['release_date'] ?? '' }}">
                        <input type="hidden" name="overview" value="{{ $movie['overview'] ?? '' }}">
                        <button type="submit" class="w-full rounded-lg border border-amber-400/40 py-1.5 text-sm text-amber-300 hover:bg-amber-400/10">
                            Añadir a mi club
                        </button>
                    </form>
                </div>
            </article>
        @endforeach
    </div>
@endsection
