<?php

namespace App\Services;

use App\Models\DetalleCompra;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ServicioDetalleCompra
{
    public function listar()
    {
        return DetalleCompra::with(['producto', 'compra'])->get();
    }

    public function obtenerPorId(int $id): Collection
    {
        return DetalleCompra::with(['producto', 'compra'])->where("compra_id", $id)->get();
    }
    public function eliminarYGuardar(Collection $almacen, string $compraId)
    {
        try {
            DetalleCompra::where("compra_id", $compraId)->delete();
            foreach ($almacen as $almc) {
                $this->crear([
                    "producto_id"   => $almc->producto_id,
                    "compra_id"     => $compraId,
                    "imei"          => $almc->imei ?? "-",
                    "color"         => $almc->color ?? "-",
                    "precio"         =>    $almc->precio_compra ?? 0,
                    "cantidad"      => $almc->cantidad ?? 1,
                    "registrado"    => $almc->registrado ?? 0,
                ]);
            }
        } catch (\Throwable $th) {
            throw new \Exception("Error al eliminar y guardar: " . $th->getMessage());
        }
    }
    public function crear(array $data): DetalleCompra
    {
        try {
            return DetalleCompra::create($data);
        } catch (Exception $e) {
            Log::error("Error al crear detalle de compra: " . $e->getMessage());
            throw new Exception("No se pudo registrar el detalle de compra.");
        }
    }

    public function actualizar(int $id, array $data): DetalleCompra
    {
        try {
            $detalle = DetalleCompra::findOrFail($id);
            $detalle->update($data);
            return $detalle;
        } catch (Exception $e) {
            Log::error("Error al actualizar detalle de compra: " . $e->getMessage());
            throw new Exception("No se pudo actualizar el detalle.");
        }
    }

    public function eliminar(int $id): bool
    {
        try {
            $detalle = DetalleCompra::findOrFail($id);
            $detalle->delete();
            return true;
        } catch (Exception $e) {
            Log::error("Error al eliminar detalle de compra: " . $e->getMessage());
            throw new Exception("No se pudo eliminar el detalle.");
        }
    }
}
