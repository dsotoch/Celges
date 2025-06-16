<?php

namespace App\Services;

use App\Models\Pagos;
use Exception;
use Illuminate\Support\Facades\Log;

class ServicioPagos
{
    public function listar()
    {
        return Pagos::with(['servicio', 'operacion', 'persona'])->get();
    }

    public function obtenerPorId(int $id): Pagos
    {
        return Pagos::with(['servicio', 'operacion', 'persona'])->findOrFail($id);
    }

    public function crear(array $data): Pagos
    {
        try {
            return Pagos::create($data);
        } catch (Exception $e) {
            Log::error("Error al registrar el pago: " . $e->getMessage());
            throw new Exception("No se pudo registrar el pago.");
        }
    }

    public function actualizar(int $id, array $data): Pagos
    {
        try {
            $pago = Pagos::findOrFail($id);
            $pago->update($data);
            return $pago;
        } catch (Exception $e) {
            Log::error("Error al actualizar el pago: " . $e->getMessage());
            throw new Exception("No se pudo actualizar el pago.");
        }
    }

    public function eliminar(int $id): bool
    {
        try {
            $pago = Pagos::findOrFail($id);
            $pago->delete();
            return true;
        } catch (Exception $e) {
            Log::error("Error al eliminar el pago: " . $e->getMessage());
            throw new Exception("No se pudo eliminar el pago.");
        }
    }
}
