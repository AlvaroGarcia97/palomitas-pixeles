<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    // GET / -> portada pública, o resumen si ya hay sesión.
    public function index()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        return view('home');
    }
}
