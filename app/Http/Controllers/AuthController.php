<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Escribe un correo válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        if (! Auth::attempt($credenciales, $request->boolean('recordar'))) {
            return back()
                ->withErrors(['email' => 'El correo o la contraseña no son correctos.'])
                ->onlyInput('email');
        }

        // Evita fijación de sesión
        $request->session()->regenerate();

        return redirect()->intended(route('vochito.inicio'))
            ->with('ok', 'Bienvenido, ' . Auth::user()->name . '.');
    }

    public function register(Request $request)
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.max' => 'El nombre no puede pasar de 120 caracteres.',
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Escribe un correo válido.',
            'email.unique' => 'Ya existe una cuenta con ese correo.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        // is_admin no se toma del formulario: toda cuenta nueva es de usuario normal
        $usuario = User::create($datos);

        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()->route('vochito.inicio')->with('ok', 'Tu cuenta se creó correctamente.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('vochito.iniciosesion')->with('success', 'Cerraste sesión correctamente.');
    }
}
