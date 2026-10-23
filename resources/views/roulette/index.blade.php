@extends('layouts.app')

@section('title', 'Ruleta · '.config('app.name'))

@section('content')
    <div class="mx-auto max-w-md text-center">
        <h1 class="mb-2 text-2xl font-bold">La ruleta</h1>
        <p class="mb-8 text-neutral-400">Que decida el azar. Solo entran las de "Quiero ver".</p>

        <div class="mx-auto w-64 overflow-hidden rounded-xl border border-white/10 bg-white/5">
            @if ($chosenPoster)
                <img id="slot" src="{{ $chosenPoster }}" alt="Película elegida" class="h-96 w-full object-cover">
            @else
                <div class="flex h-96 items-center justify-center text-neutral-500">Sin carátula</div>
            @endif
        </div>

        {{-- Empieza oculto (opacity-0); el JS lo muestra al terminar el giro. --}}
        <div id="result" class="opacity-0 transition-opacity duration-500">
            <h2 class="mt-6 text-xl font-semibold">{{ $chosen->title }}</h2>
            <p class="mt-1 text-sm text-neutral-400">{{ substr($chosen->release_date ?? '', 0, 4) ?: '—' }}</p>

            <div class="mt-6 flex justify-center gap-3">
                <a href="{{ route('library.show', $chosen->tmdb_id) }}"
                   class="rounded-full bg-amber-400 px-5 py-2 font-semibold text-black hover:bg-amber-300">
                    Ver ficha
                </a>
                <a href="{{ route('roulette.index') }}"
                   class="rounded-full border border-white/15 px-5 py-2 font-semibold hover:bg-white/5">
                    Girar otra vez
                </a>
            </div>
        </div>
    </div>

    @if ($chosenPoster)
        <script>
            // Carátulas disponibles para el "giro" y la elegida al final.
            const posters = @json($posters.length ? $posters : [$chosenPoster]);
            const chosen = @json($chosenPoster);
            const slot = document.getElementById('slot');
            const result = document.getElementById('result');

            if (posters.length <= 1) {
                // Solo hay una opción: no hay nada que girar.
                result.classList.remove('opacity-0');
            } else {
                let ticks = 0;

                // setInterval ejecuta una función cada X milisegundos.
                const timer = setInterval(() => {
                    slot.src = posters[Math.floor(Math.random() * posters.length)];

                    // Tras 18 cambios, paramos y mostramos la elegida.
                    if (++ticks >= 18) {
                        clearInterval(timer);
                        slot.src = chosen;
                        result.classList.remove('opacity-0');
                    }
                }, 90);
            }
        </script>
    @else
        <script>
            document.getElementById('result').classList.remove('opacity-0');
        </script>
    @endif
@endsection
