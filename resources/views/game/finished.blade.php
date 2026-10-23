@extends('layouts.app')

@section('title', 'Fin del juego · '.config('app.name'))

@section('content')
    <div class="mx-auto max-w-md py-16 text-center">
        <p class="text-neutral-400">Partida terminada</p>

        <p class="mt-2 text-6xl font-bold">
            {{ $finalScore }}<span class="text-2xl text-neutral-500">/10</span>
        </p>

        <div class="mt-10 flex justify-center gap-3">
            <a href="{{ route('game.restart') }}"
               class="rounded-full bg-amber-400 px-6 py-2 font-semibold text-black hover:bg-amber-300">
                Jugar otra vez
            </a>
            <a href="{{ route('library.index') }}"
               class="rounded-full border border-white/15 px-6 py-2 font-semibold hover:bg-white/5">
                Mi club
            </a>
        </div>
    </div>
@endsection
