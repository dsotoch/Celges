<?php

namespace App\Services;

use App\Models\Operacion;
use Exception;
use Illuminate\Support\Facades\Log;

class ServicioOperacion
{
    public function crear(array $data, string $tipo): Operacion
    {
        try {
            return Operacion::create([
                "numero" => $data["numero"]??"0",
                "tipo" => $tipo,
                "monto" => $data["monto"],
                "fecha" => $data["fecha"],
                "cuenta_id" => $data["cuenta_id"],
            ]);
        } catch (Exception $e) {
            Log::error("Error al crear operación: " . $e->getMessage());
            throw new Exception("No se pudo crear la operación.");
        }
    }

    public function actualizar(int $id, array $data): Operacion
    {
        try {
            $operacion = Operacion::findOrFail($id);
            $operacion->update($data);
            return $operacion;
        } catch (Exception $e) {
            Log::error("Error al actualizar operación: " . $e->getMessage());
            throw new Exception("No se pudo actualizar la operación.");
        }
    }

    public function eliminar(int $id): bool
    {
        try {
            $operacion = Operacion::findOrFail($id);
            return $operacion->delete();
        } catch (Exception $e) {
            Log::error("Error al eliminar operación: " . $e->getMessage());
            throw new Exception("No se pudo eliminar la operación.");
        }
    }

    public function obtenerPorId(int $id): Operacion
    {
        return Operacion::with("cuenta")->findOrFail($id);
    }

    public function listar()
    {
        return Operacion::with("cuenta")->all();
    }
}
