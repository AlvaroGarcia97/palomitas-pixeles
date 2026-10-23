@extends('layouts.app')

@section('title', $movie['title'].' · '.config('app.name'))

@section('content')
    <div class="grid gap-8 md:grid-cols-[300px_1fr]">
        {{-- Carátula --}}
        <div>
            @if (! empty($movie['poster_path']))
                <img src="https://image.tmdb.org/t/p/w342{{ $movie['poster_path'] }}"
                     alt="{{ $movie['title'] }}" class="w-full rounded-xl">
            @else
                <div class="flex h-96 items-center justify-center rounded-xl bg-white/5 text-neutral-500">Sin carátula</div>
            @endif
        </div>

        {{-- Datos + formulario --}}
        <div>
            <h1 class="text-3xl font-bold">{{ $movie['title'] }}</h1>
            <p class="mt-1 text-neutral-400">
                {{ substr($movie['release_date'] ?? '', 0, 4) ?: '—' }}
                · {{ number_format($movie['vote_average'] ?? 0, 1) }}/10
            </p>
            <p class="mt-4 text-neutral-300">{{ $movie['overview'] ?: 'Sin sinopsis disponible.' }}</p>

            <div class="mt-8 border-t border-white/10 pt-6">
                @if ($item)
                    {{-- Ya está en el club: formulario para editar estado, nota y reseña. --}}
                    <h2 class="mb-4 font-semibold">Tu valoración</h2>

                    <form method="POST" action="{{ route('library.update', $item->tmdb_id) }}" class="space-y-4">
                        @csrf
                        @method('PUT') {{-- HTML no envía PUT: Laravel lo simula con este campo. --}}

                        <div>
                            <label class="mb-1 block text-sm text-neutral-400">Estado</label>
                            <select name="status" class="w-full rounded-lg border border-white/10 bg-white/5 px-4 py-2 outline-none focus:border-amber-400">
                                <option value="watchlist" @selected($item->status === 'watchlist')>Quiero verla</option>
                                <option value="watched" @selected($item->status === 'watched')>Vista</option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm text-neutral-400">Nota (1 a 5)</label>
                            <select name="rating" class="w-full rounded-lg border border-white/10 bg-white/5 px-4 py-2 outline-none focus:border-amber-400">
                                <option value="">Sin nota</option>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" @selected($item->rating === $i)>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm text-neutral-400">Reseña</label>
                            <textarea name="review" rows="3"
                                      class="w-full rounded-lg border border-white/10 bg-white/5 px-4 py-2 outline-none focus:border-amber-400">{{ old('review', $item->review) }}</textarea>
                        </div>

                        <button type="submit" class="rounded-lg bg-amber-400 px-5 py-2 font-semibold text-black hover:bg-amber-300">
                            Guardar cambios
                        </button>
                    </form>

                    <form method="POST" action="{{ route('library.destroy', $item->tmdb_id) }}" class="mt-4"
                          onsubmit="return confirm('¿Quitar esta película de tu club?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-sm text-red-400 hover:underline">Quitar de mi club</button>
                    </form>
                @else
                    {{-- No está en el club: botón para añadirla. --}}
                    <form method="POST" action="{{ route('library.store') }}">
                        @csrf
                        <input type="hidden" name="tmdb_id" value="{{ $movie['id'] }}">
                        <input type="hidden" name="title" value="{{ $movie['title'] }}">
                        <input type="hidden" name="poster_path" value="{{ $movie['poster_path'] }}">
                        <input type="hidden" name="release_date" value="{{ $movie['release_date'] ?? '' }}">
                        <input type="hidden" name="overview" value="{{ $movie['overview'] ?? '' }}">
                        <button type="submit" class="rounded-lg bg-amber-400 px-5 py-2 font-semibold text-black hover:bg-amber-300">
                            Añadir a mi club
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
