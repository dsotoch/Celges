<?php

namespace App\Services;

use App\Models\Venta;
use Exception;
use Illuminate\Support\Facades\Log;

class ServicioVenta
{
    
     public function listar()
    {
        return Venta::with(['cliente', 'detalles', 'abonos'])->get();
    }

    public function obtenerPorId(int $id): Venta
    {
        return Venta::with(['cliente', 'detalles', 'abonos'])->findOrFail($id);
    }

    public function crear(array $data): Venta
    {
        try {
            return Venta::create($data);
        } catch (Exception $e) {
            Log::error("Error al crear venta: " . $e->getMessage());
            throw new Exception("No se pudo crear la venta.");
        }
    }

    public function actualizar(int $id, array $data): Venta
    {
        try {
            $venta = Venta::findOrFail($id);
            $venta->update($data);
            return $venta;
        } catch (Exception $e) {
            Log::error("Error al actualizar venta: " . $e->getMessage());
            throw new Exception("No se pudo actualizar la venta.");
        }
    }

    public function eliminar(int $id): bool
    {
        try {
            $venta = Venta::findOrFail($id);
            $venta->delete();
            return true;
        } catch (Exception $e) {
            Log::error("Error al eliminar venta: " . $e->getMessage());
            throw new Exception("No se pudo eliminar la venta.");
        }
    }
}
