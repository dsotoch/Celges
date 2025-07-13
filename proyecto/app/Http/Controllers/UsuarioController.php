<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{


    public function index()
    {
        $usuarios = User::all();
        $roles = Role::all();
        $permisos = Permission::all();
        return view('usuarios.index', compact('usuarios', "roles", "permisos"));
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', '✅ Usuario eliminado correctamente.');
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redireccionar según el rol
            if (Auth::user()->hasRole('admin')) {
                return redirect()->route('dashboard.admin');
            } elseif (Auth::user()->hasRole('vendedor')) {
                return redirect()->route('ventas.index');
            } elseif (Auth::user()->hasRole('almacenero')) {
                return redirect()->route('productos.index');
            } else {
                return redirect()->route('home');
            }
        }

        return back()->with('error', 'Credenciales incorrectas.');
    }
    public function logout(Request $request)
    {
        Auth::logout(); // Cierra la sesión del usuario

        $request->session()->invalidate(); // Invalida la sesión
        $request->session()->regenerateToken(); // Regenera el token CSRF

        return redirect()->route('login')->with('success', '✅ Sesión cerrada correctamente.');
    }
    public function store(Request $request)
    {
        // Verifica si ya existe un usuario con ese email
        $userExistente = User::where("email", $request->email)->first();

        if ($userExistente) {
            // Si se está actualizando, permitir si el email pertenece al mismo usuario
            if (!$request->id || $userExistente->id != $request->id) {
                return redirect()->back()->withErrors(['error' => '🔴 El Email ya se encuentra registrado.']);
            }
        }

        if ($request->id) {
            // ACTUALIZAR
            $user = User::findOrFail($request->id);
            $user->name = $request->name;
            $user->email = $request->email;

            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }

            $user->rol = $request->role;
            $user->save();

            // Sincroniza roles (quita los anteriores y asigna el nuevo)
            $user->syncRoles([$request->role]);
        } else {
            if (!$request->filled('password')) {
                return redirect()->back()->withErrors(['error' => '🔴 Ingresa Una Contraseña.']);
            }

            // CREAR
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                "rol" => $request->role
            ]);

            // Asigna el rol
            $user->assignRole($request->role);
        }

        return redirect()->back()->with('success', '✅ Usuario guardado correctamente.');
    }
}
