<?php

namespace App\Services;

use App\Models\AlmacenInterno;
use App\Models\DetalleVenta;
use App\Models\Producto;
use Exception;
use Illuminate\Support\Facades\Log;

class ServicioDetalleVentas
{
    public function listar()
    {
        return DetalleVenta::with(['venta', 'producto'])->get();
    }

    public function obtenerPorIdTodaVenta(int $ventaid)
    {
        return DetalleVenta::with(['venta', 'producto'])->where("venta_id", $ventaid)->get();
    }
    public function obtenerPorId(int $id): DetalleVenta
    {
        return DetalleVenta::with(['venta', 'producto'])->findOrFail($id);
    }

    public function crear(array $data): DetalleVenta
    {
        try {
            return DetalleVenta::create($data);
        } catch (Exception $e) {
            Log::error("Error al crear detalle de venta: " . $e->getMessage());
            throw new Exception("No se pudo crear el detalle de venta.");
        }
    }

    public function actualizar(int $id, array $data): DetalleVenta
    {
        try {
            $detalle = DetalleVenta::findOrFail($id);
            $detalle->update($data);
            return $detalle;
        } catch (Exception $e) {
            Log::error("Error al actualizar detalle de venta: " . $e->getMessage());
            throw new Exception("No se pudo actualizar el detalle de venta.");
        }
    }

    public function actualizarPorProductoYImeis(int $productoId, array $imeis, int $compraId): array
    {
        $detallesActualizados = [];

        // Verificar si el producto existe y es de tipo "OTRO"
        $producto = Producto::find($productoId);
        $esTipoOtro = $producto && $producto->tipo === "OTRO";

        foreach ($imeis as $imeiData) {
            $codigo = $imeiData['codigo'];
            $descripcion = $imeiData['descripcion'];



            if (!$esTipoOtro) {
                // Buscar detalle de venta correspondiente
                $detalle = DetalleVenta::where('producto_id', $productoId)
                    ->where('descripcion', $descripcion)
                    ->where('venta_id', $compraId)
                    ->first();

                if ($detalle) {
                    $detalle->update([
                        'imei' => $codigo,
                    ]);

                    $detallesActualizados[] = $detalle;
                } else {
                    Log::warning("No se encontró detalle para producto_id {$productoId}, descripción '{$descripcion}' con IMEI {$codigo}");
                }
            } else {
                // Producto tipo OTRO: reducir la cantidad en el almacén correspondiente
                $almacen = AlmacenInterno::where('producto_id', $productoId)
                    ->where('cantidad', '>', 0)
                    ->orderBy('id')
                    ->first();

                if ($almacen) {
                    $almacen->decrement('cantidad');
                } else {
                    Log::warning("No se encontró en almacén para producto_id {$productoId} con descripción '{$descripcion}'");
                }
            }
        }

        return $detallesActualizados;
    }





    public function eliminar(int $id): bool
    {
        try {
            $detalle = DetalleVenta::findOrFail($id);
            $detalle->delete();
            return true;
        } catch (Exception $e) {
            Log::error("Error al eliminar detalle de venta: " . $e->getMessage());
            throw new Exception("No se pudo eliminar el detalle de venta.");
        }
    }
}
