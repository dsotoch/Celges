<?php

namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Auth;

class Usuario
{
    public function iniciarSesion(array $datos): User
    {
        try {
            $usuario = User::where('email', $datos["email"])->firstOrFail();

            if (!$usuario->estado_activo) {
                throw new Exception("Tu cuenta está inactiva. Comunícate con el administrador.");
            }

            if (!Auth::attempt($datos)) {
                throw new Exception("Contraseña inválida.");
            }

            return $usuario;
        } catch (\Exception $e) {
            Log::error("Error iniciando sesión: " . $e->getMessage());
            throw $e; // puedes lanzar el mismo error que ocurrió
        }
    }

    public function cerrarSesion(): bool
    {
        Auth::logout();
        return true;
    }

    public function crearUsuario(array $datos): User
    {
        try {
            return User::create($datos);
        } catch (Exception $e) {
            Log::error('Error al crear usuario: ' . $e->getMessage());
            throw new Exception("No se pudo crear el usuario.");
        }
    }

    public function editarUsuario(int $id, array $datos): User
    {
        try {
            $usuario = User::findOrFail($id);
            $usuario->update($datos);
            $usuario->save();

            return $usuario;
        } catch (Exception $e) {
            Log::error('Error al editar usuario: ' . $e->getMessage());
            throw new Exception("No se pudo editar el usuario.");
        }
    }

    public function eliminarUsuario(int $id): bool
    {
        try {
            $usuario = User::findOrFail($id);
            $usuario->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('Error al eliminar el usuario: ' . $e->getMessage());
            throw new Exception("No se pudo eliminar el usuario.");
        }
    }
}
