<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // GET /registro -> muestra el formulario de registro.
    public function showRegister()
    {
        return view('auth.register');
    }

    // POST /registro -> crea el usuario y lo deja con sesión iniciada.
    public function register(Request $request)
    {
        // validate() comprueba los datos; si fallan, vuelve atrás con los errores.
        // 'confirmed' exige un campo "password_confirmation" igual a "password".
        // 'unique:users,email' impide emails repetidos.
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:50'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Creamos el usuario. La contraseña se cifra sola (cast 'hashed' del modelo).
        $user = User::create($data);

        // Iniciamos sesión automáticamente tras registrarse.
        Auth::login($user);

        return redirect()->route('home');
    }

    // GET /login -> muestra el formulario de entrada.
    public function showLogin()
    {
        return view('auth.login');
    }

    // POST /login -> comprueba las credenciales e inicia sesión.
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // attempt() busca un usuario con ese email y comprueba la contraseña.
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Seguridad: regeneramos el id de sesión para evitar "session fixation".
            $request->session()->regenerate();

            return redirect()->intended(route('home'));
        }

        // Si falla, volvemos atrás con un error y sin perder el email escrito.
        return back()
            ->withErrors(['email' => 'El email o la contraseña no coinciden.'])
            ->onlyInput('email');
    }

    // POST /logout -> cierra la sesión.
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();       // borra los datos de la sesión.
        $request->session()->regenerateToken();  // cambia el token CSRF.

        return redirect()->route('home');
    }
}
