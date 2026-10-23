<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\MovieSearchController;
use App\Http\Controllers\RouletteController;
use Illuminate\Support\Facades\Route;

// Portada pública (con sesión redirige al resumen).
Route::get('/', [HomeController::class, 'index'])->name('home');

// Resumen del usuario (solo con sesión).
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// Rutas solo para quien NO ha iniciado sesión (registro y login).
Route::middleware('guest')->group(function () {
    Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [AuthController::class, 'register']);

    // Se llama "login" a propósito: el middleware 'auth' redirige aquí a los invitados.
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Cerrar sesión (solo si has iniciado sesión).
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Buscador de películas (solo con sesión iniciada).
Route::get('/buscar', [MovieSearchController::class, 'index'])
    ->middleware('auth')
    ->name('movies.search');

// Mi club: películas guardadas por el usuario (solo con sesión).
Route::middleware('auth')->prefix('club')->name('library.')->group(function () {
    Route::get('/', [LibraryController::class, 'index'])->name('index');
    Route::post('/', [LibraryController::class, 'store'])->name('store');
    Route::get('/{tmdbId}', [LibraryController::class, 'show'])->whereNumber('tmdbId')->name('show');
    Route::put('/{tmdbId}', [LibraryController::class, 'update'])->whereNumber('tmdbId')->name('update');
    Route::delete('/{tmdbId}', [LibraryController::class, 'destroy'])->whereNumber('tmdbId')->name('destroy');
});

// Ruleta: elige al azar una película pendiente (solo con sesión).
Route::get('/ruleta', [RouletteController::class, 'index'])
    ->middleware('auth')
    ->name('roulette.index');

// Juego "¿Qué película es?" (solo con sesión).
Route::middleware('auth')->prefix('juego')->name('game.')->group(function () {
    Route::get('/', [GameController::class, 'index'])->name('index');
    Route::post('/respuesta', [GameController::class, 'answer'])->name('answer');
    Route::get('/siguiente', [GameController::class, 'next'])->name('next');
    Route::get('/reiniciar', [GameController::class, 'restart'])->name('restart');
});
