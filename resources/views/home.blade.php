@extends('layouts.app')

@section('title', config('app.name'))

@section('content')
    <section class="py-20 text-center">
        <h1 class="text-4xl font-bold sm:text-5xl">Palomitas &amp; Pixeles</h1>

        <p class="mx-auto mt-4 max-w-2xl text-neutral-400">
            Tu club de cine: guarda las películas que quieres ver, puntúa las que ya has visto
            y deja que la ruleta decida qué toca esta noche.
        </p>

        <div class="mt-8 flex justify-center gap-3">
            @auth
                <a href="{{ route('movies.search') }}" class="rounded-full bg-amber-400 px-6 py-3 font-semibold text-black hover:bg-amber-300">
                    Buscar películas
                </a>
            @else
                <a href="{{ route('register') }}" class="rounded-full bg-amber-400 px-6 py-3 font-semibold text-black hover:bg-amber-300">
                    Crear cuenta
                </a>
                <a href="{{ route('login') }}" class="rounded-full border border-white/15 px-6 py-3 font-semibold hover:bg-white/5">
                    Entrar
                </a>
            @endauth
        </div>
    </section>
@endsection
