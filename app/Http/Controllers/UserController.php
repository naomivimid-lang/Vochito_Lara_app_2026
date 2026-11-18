<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $usuarios = User::query()
            ->when($request->buscar, fn ($q, $b) => $q->where(fn ($q) => $q
                ->where('name', 'ilike', "%{$b}%")
                ->orWhere('email', 'ilike', "%{$b}%")))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('vochito.usuarios', compact('usuarios'));
    }

    public function edit(User $usuario)
    {
        return view('vochito.editar_usuario', compact('usuario'));
    }

    public function update(Request $request, User $usuario)
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($usuario)],
            'password' => ['nullable', Password::min(8)],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Escribe un correo válido.',
            'email.unique' => 'Ya existe otra cuenta con ese correo.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        if (blank($datos['password'] ?? null)) {
            unset($datos['password']);
        }

        // Un administrador no puede quitarse a sí mismo el permiso y quedarse fuera
        $usuario->is_admin = $usuario->is($request->user()) ? true : $request->boolean('is_admin');
        $usuario->fill($datos)->save();

        return redirect()->route('usuarios.index')->with('ok', 'Usuario actualizado.');
    }

    public function destroy(Request $request, User $usuario)
    {
        if ($usuario->is($request->user())) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta mientras tienes la sesión iniciada.');
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')->with('ok', 'Usuario eliminado.');
    }
}
