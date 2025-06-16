<?php

namespace App\Services;

use App\Models\Persona;
use Exception;
use Illuminate\Support\Facades\Log;

class ServicioPersona
{
    public function crear(array $data): Persona
    {
        try {
            return Persona::create($data);
        } catch (Exception $e) {
            Log::error("Error al crear persona: " . $e->getMessage());
            throw new Exception("No se pudo crear la persona.");
        }
    }

    public function actualizar(int $id, array $data): Persona
    {
        try {
            $persona = Persona::findOrFail($id);
            $persona->update($data);
            return $persona;
        } catch (Exception $e) {
            Log::error("Error al actualizar persona: " . $e->getMessage());
            throw new Exception("No se pudo actualizar la persona.");
        }
    }

    public function eliminar(int $id): bool
    {
        try {
            $persona = Persona::findOrFail($id);
            return $persona->delete();
        } catch (Exception $e) {
            Log::error("Error al eliminar persona: " . $e->getMessage());
            throw new Exception("No se pudo eliminar la persona.");
        }
    }

    public function obtenerPorId(int $id): Persona
    {
        return Persona::findOrFail($id);
    }

    public function listar()
    {
        return Persona::all();
    }
}
