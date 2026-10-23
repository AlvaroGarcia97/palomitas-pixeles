@extends('layouts.app')

@section('title', 'Entrar · '.config('app.name'))

@section('content')
    <div class="mx-auto max-w-md">
        <h1 class="mb-6 text-2xl font-bold">Entrar</h1>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            {{-- @csrf: candado de seguridad que Laravel exige en los formularios POST. --}}
            @csrf

            <div>
                <label class="mb-1 block text-sm text-neutral-400">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full rounded-lg border border-white/10 bg-white/5 px-4 py-2 outline-none focus:border-amber-400">
                {{-- @error muestra el mensaje de error de ese campo (si lo hay). --}}
                @error('email')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm text-neutral-400">Contraseña</label>
                <input type="password" name="password" required
                       class="w-full rounded-lg border border-white/10 bg-white/5 px-4 py-2 outline-none focus:border-amber-400">
            </div>

            <button type="submit" class="w-full rounded-lg bg-amber-400 py-2 font-semibold text-black hover:bg-amber-300">
                Entrar
            </button>
        </form>

        <p class="mt-4 text-sm text-neutral-400">
            ¿No tienes cuenta? <a href="{{ route('register') }}" class="text-amber-400 hover:underline">Crear una</a>
        </p>
    </div>
@endsection
