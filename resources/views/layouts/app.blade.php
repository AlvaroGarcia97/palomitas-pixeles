<!DOCTYPE html>
{{-- ESQUELETO COMÚN de todas las páginas del proyecto. --}}
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', config('app.name'))</title>

    {{-- Tailwind desde CDN: estilos sin instalar nada. --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-neutral-950 text-neutral-100 antialiased">

    <header class="border-b border-white/10">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
            <a href="{{ route('home') }}" class="font-bold text-amber-300">{{ config('app.name') }}</a>

            <nav class="flex items-center gap-4 text-sm">
                @auth
                    {{-- Si hay sesión: resumen, buscador, club, ruleta, juego y cerrar sesión. --}}
                    <a href="{{ route('dashboard') }}" class="text-neutral-300 hover:text-white">Resumen</a>
                    <a href="{{ route('movies.search') }}" class="text-neutral-300 hover:text-white">Buscar</a>
                    <a href="{{ route('library.index') }}" class="text-neutral-300 hover:text-white">Mi club</a>
                    <a href="{{ route('roulette.index') }}" class="text-neutral-300 hover:text-white">Ruleta</a>
                    <a href="{{ route('game.index') }}" class="text-neutral-300 hover:text-white">Juego</a>
                    <span class="text-neutral-400">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-neutral-300 hover:text-white">Cerrar sesión</button>
                    </form>
                @else
                    {{-- Si no: entrar o crear cuenta. --}}
                    <a href="{{ route('login') }}" class="text-neutral-300 hover:text-white">Entrar</a>
                    <a href="{{ route('register') }}" class="rounded-full bg-amber-400 px-4 py-1.5 font-semibold text-black hover:bg-amber-300">
                        Crear cuenta
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-6 py-10">
        {{-- Mensaje de éxito guardado con ->with('ok', ...) en el controlador. --}}
        @if (session('ok'))
            <div class="mb-6 rounded-lg border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-emerald-300">
                {{ session('ok') }}
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>
