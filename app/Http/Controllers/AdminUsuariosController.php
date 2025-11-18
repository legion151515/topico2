<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminUsuariosController extends Controller
{
    /**
     * Mostrar lista de usuarios del personal
     */
    public function index()
    {
        // Solo mostrar usuarios que NO sean estudiantes
        $usuarios = User::whereIn('tipo_usuario', ['admin', 'medico', 'enfermero', 'recepcionista'])
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Mostrar formulario para crear nuevo usuario
     */
    public function create()
    {
        return view('admin.usuarios.create');
    }

    /**
     * Guardar nuevo usuario del personal
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'dni' => ['required', 'digits:8', 'unique:users,dni'],
            'tipo_usuario' => ['required', 'in:admin,medico,enfermero,recepcionista'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'dni' => $request->dni,
            'tipo_usuario' => $request->tipo_usuario,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.usuarios.index')
                         ->with('success', 'Usuario del personal creado exitosamente.');
    }

    /**
     * Mostrar formulario para editar usuario
     */
    public function edit(User $usuario)
    {
        // Solo permitir editar usuarios del personal, no estudiantes
        if ($usuario->tipo_usuario === 'estudiante') {
            abort(403, 'No puedes editar usuarios estudiantes desde aquí.');
        }

        return view('admin.usuarios.edit', compact('usuario'));
    }

    /**
     * Actualizar usuario del personal
     */
    public function update(Request $request, User $usuario)
    {
        // Solo permitir actualizar usuarios del personal, no estudiantes
        if ($usuario->tipo_usuario === 'estudiante') {
            abort(403, 'No puedes editar usuarios estudiantes desde aquí.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $usuario->id],
            'dni' => ['required', 'digits:8', 'unique:users,dni,' . $usuario->id],
            'tipo_usuario' => ['required', 'in:admin,medico,enfermero,recepcionista'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->dni = $request->dni;
        $usuario->tipo_usuario = $request->tipo_usuario;

        // Solo actualizar contraseña si se proporciona una nueva
        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        return redirect()->route('admin.usuarios.index')
                         ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Eliminar usuario del personal
     */
    public function destroy(User $usuario)
    {
        // Solo permitir eliminar usuarios del personal, no estudiantes
        if ($usuario->tipo_usuario === 'estudiante') {
            abort(403, 'No puedes eliminar usuarios estudiantes desde aquí.');
        }

        // Evitar que el admin se elimine a sí mismo
        if ($usuario->id === auth()->id()) {
            return redirect()->route('admin.usuarios.index')
                             ->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $usuario->delete();

        return redirect()->route('admin.usuarios.index')
                         ->with('success', 'Usuario eliminado exitosamente.');
    }
}
