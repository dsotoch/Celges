<?php

namespace App\Services;

use App\Models\Servicio;
use Exception;
use Illuminate\Support\Facades\Log;

class ServicioServicio
{
    public function crear(array $data): Servicio
    {
        try {
            return Servicio::create($data);
        } catch (Exception $e) {
            Log::error("Error al crear servicio: " . $e->getMessage());
            throw new Exception("No se pudo crear el servicio.");
        }
    }

    public function actualizar(int $id, array $data): Servicio
    {
        try {
            $servicio = Servicio::findOrFail($id);
            $servicio->update($data);
            return $servicio;
        } catch (Exception $e) {
            Log::error("Error al actualizar servicio: " . $e->getMessage());
            throw new Exception("No se pudo actualizar el servicio.");
        }
    }

    public function eliminar(int $id): bool
    {
        try {
            $servicio = Servicio::findOrFail($id);
            return $servicio->delete();
        } catch (Exception $e) {
            Log::error("Error al eliminar servicio: " . $e->getMessage());
            throw new Exception("No se pudo eliminar el servicio.");
        }
    }

    public function obtenerPorId(int $id): Servicio
    {
        return Servicio::findOrFail($id);
    }

    public function listar()
    {
        return Servicio::all();
    }
}
