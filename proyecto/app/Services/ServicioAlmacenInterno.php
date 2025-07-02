<?php

namespace App\Services;

use App\Models\AlmacenInterno;
use Exception;
use Illuminate\Support\Facades\Log;

class ServicioAlmacenInterno
{
    public function listar()
    {
        return AlmacenInterno::with(['compra', 'producto'])->get();
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
}
