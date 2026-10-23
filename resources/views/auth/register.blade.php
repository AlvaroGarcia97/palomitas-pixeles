@extends('layouts.app')

@section('title', 'Crear cuenta · '.config('app.name'))

@section('content')
    <div class="mx-auto max-w-md">
        <h1 class="mb-6 text-2xl font-bold">Crear cuenta</h1>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label class="mb-1 block text-sm text-neutral-400">Nombre</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full rounded-lg border border-white/10 bg-white/5 px-4 py-2 outline-none focus:border-amber-400">
                @error('name')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm text-neutral-400">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full rounded-lg border border-white/10 bg-white/5 px-4 py-2 outline-none focus:border-amber-400">
                @error('email')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm text-neutral-400">Contraseña</label>
                <input type="password" name="password" required
                       class="w-full rounded-lg border border-white/10 bg-white/5 px-4 py-2 outline-none focus:border-amber-400">
                @error('password')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm text-neutral-400">Repite la contraseña</label>
                {{-- El nombre "password_confirmation" lo exige la regla 'confirmed'. --}}
                <input type="password" name="password_confirmation" required
                       class="w-full rounded-lg border border-white/10 bg-white/5 px-4 py-2 outline-none focus:border-amber-400">
            </div>

            <button type="submit" class="w-full rounded-lg bg-amber-400 py-2 font-semibold text-black hover:bg-amber-300">
                Crear cuenta
            </button>
        </form>

        <p class="mt-4 text-sm text-neutral-400">
            ¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-amber-400 hover:underline">Entrar</a>
        </p>
    </div>
@endsection
