<?php

namespace App\Services;

use App\Models\AlmacenInterno;
use App\Models\Producto;
use Exception;
use Illuminate\Support\Facades\Log;

class ServicioAlmacenInterno
{
    public function clonarYActualizar($compraId, $productoId, $datos, $registrado)
    {
        $base = AlmacenInterno::where('compra_id', $compraId)
            ->where('producto_id', $productoId)
            ->where('registrado', $registrado)
            ->first();

        if (!$base) {
            throw new \Exception("No se puede clonar porque no hay base.");
        }

        $clon = $base->replicate();
        $clon->cantidad = 1;
        $clon->fill($datos);
        $clon->save();
    }
     public function listarProductosDeCompra(string $compraId)
    {
        return AlmacenInterno::where('compra_id', $compraId)->get();
    }
    public function actualizarProductoDisponible($compraId, $productoId, $datos, $registrado)
    {
        $producto = Producto::find($productoId);

        if (!$producto) {
            return false;
        }

        if ($producto->tipo == 'CELULAR') {
            $almacen = AlmacenInterno::where('compra_id', $compraId)
                ->where('producto_id', $productoId)
                ->where('registrado', $registrado)
                ->where('imei', '-')
                ->first();
        } else {
            // Para otros productos, tomar el primero disponible
            $almacen = AlmacenInterno::where('compra_id', $compraId)
                ->where('producto_id', $productoId)
                ->where('registrado', $registrado)
                ->first();
        }

        if ($almacen) {
            $almacen->update($datos);
            return true;
        }

        return false;
    }

    public function listar()
    {
        return AlmacenInterno::with(['compra', 'producto'])->get();
    }
    public function buscarPorProductoYDescripcion($productoId, $descripcion)
    {
        return AlmacenInterno::with("compra", "compra.persona", "producto")->where('producto_id', $productoId)
            ->where('registrado', $descripcion)->where("cantidad", ">", "0")
            ->get();
    }


    public function obtenerPorId(int $id): AlmacenInterno
    {
        return AlmacenInterno::with(['compra', 'producto'])->findOrFail($id);
    }

    public function crear(array $data): AlmacenInterno
    {
        try {
            return AlmacenInterno::create([
                "compra_id" => $data["compra_id"],
                "producto_id" => $data["producto_id"],
                "imei" => $data["imei"],
                "color" => $data["color"],
                "precio_compra" => $data["precio"],
                "precio_venta" => "0.00",
                "cantidad" => $data["cantidad"],
                "registrado" => $data["registrado"]
            ]);
        } catch (Exception $e) {
            Log::error("Error al crear registro en almacén interno: " . $e->getMessage());
            throw new Exception("No se pudo crear el registro en almacén interno.");
        }
    }

    public function actualizar(int $id, array $data): AlmacenInterno
    {
        try {
            $almacen = AlmacenInterno::findOrFail($id);
            $almacen->update($data);
            return $almacen;
        } catch (Exception $e) {
            Log::error("Error al actualizar registro de almacén interno: " . $e->getMessage());
            throw new Exception("No se pudo actualizar el registro.");
        }
    }

    public function actualizarPorImei(string $imei, array $data)
    {
        try {
            $almacenes = AlmacenInterno::where("imei", $imei)->get();

            foreach ($almacenes as $almacen) {
                $almacen->update($data);
            }

            return $almacenes;
        } catch (\Exception $e) {
            Log::error("Error al actualizar registro de almacén interno con IMEI {$imei}: " . $e->getMessage());
            throw new \Exception("No se pudo actualizar el registro con IMEI {$imei}.");
        }
    }


    public function eliminar(int $id): bool
    {
        try {
            $almacenes = AlmacenInterno::where('compra_id', $id)->get();

            foreach ($almacenes as $almacen) {
                $almacen->delete();
            }

            return true;
        } catch (Exception $e) {
            Log::error("Error al eliminar registro de almacén interno: " . $e->getMessage());
            throw new Exception("No se pudo eliminar el registro.");
        }
    }

    public function eliminarProductoporImei(array $imeis): bool
    {
        try {
            $codigos = array_column($imeis, 'codigo');

            AlmacenInterno::whereIn('imei', $codigos)->update(['cantidad' => 0]);

            return true;
        } catch (Exception $e) {
            Log::error("Error al actualizar cantidad a 0 en almacén interno: " . $e->getMessage());
            throw new Exception("No se pudo actualizar el/los registro(s).");
        }
    }
}
