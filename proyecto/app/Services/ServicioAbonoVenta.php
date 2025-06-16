<?php

namespace App\Services;

use App\Models\AbonoVenta;
use Exception;
use Illuminate\Support\Facades\Log;

class ServicioAbonoVenta
{
    public function listar()
    {
        return AbonoVenta::with(['venta', 'operacion'])->get();
    }

    public function obtenerPorId(int $id): AbonoVenta
    {
        return AbonoVenta::with(['venta', 'operacion'])->findOrFail($id);
    }

    public function crear(array $data): AbonoVenta
    {
        try {
            return AbonoVenta::create($data);
        } catch (Exception $e) {
            Log::error("Error al crear abono de venta: " . $e->getMessage());
            throw new Exception("No se pudo registrar el abono.");
        }
    }

    public function actualizar(int $id, array $data): AbonoVenta
    {
        try {
            $abono = AbonoVenta::findOrFail($id);
            $abono->update($data);
            return $abono;
        } catch (Exception $e) {
            Log::error("Error al actualizar abono de venta: " . $e->getMessage());
            throw new Exception("No se pudo actualizar el abono.");
        }
    }

    public function eliminar(int $id): bool
    {
        try {
            $abono = AbonoVenta::findOrFail($id);
            $abono->delete();
            return true;
        } catch (Exception $e) {
            Log::error("Error al eliminar abono de venta: " . $e->getMessage());
            throw new Exception("No se pudo eliminar el abono.");
        }
    }
}
