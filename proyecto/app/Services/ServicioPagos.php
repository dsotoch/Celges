<?php

namespace App\Services;

use App\Models\Pagos;
use Exception;
use Illuminate\Support\Facades\Log;

class ServicioPagos
{

    public function obtenerPagosCompra(string $idCompra)
    {
        return Pagos::with(['servicio', 'operacion', 'persona'])
            ->where('nota', 'like', "%$idCompra%")
            ->orWhereHas('operacion', function ($q) use ($idCompra) {
                $q->where('numero', 'like', "%$idCompra%");
            })
            ->get();
    }

    public function listarPendientes()
    {
        return Pagos::with(['servicio', 'operacion', 'persona'])->where("metodo_pago", null)->get();
    }
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
    public function eliminarMasAntiguo(string $servicio_id): bool
    {
        try {
            $pago = Pagos::where("servicio_id", $servicio_id)->orderBy('created_at', 'asc')->first();

            if (!$pago) {
                throw new Exception("No hay pagos registrados para eliminar.");
            }

            $pago->delete();
            return true;
        } catch (Exception $e) {
            Log::error("Error al eliminar el pago más antiguo: " . $e->getMessage());
            throw new Exception("No se pudo eliminar el pago más antiguo.");
        }
    }
}
