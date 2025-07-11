<?php

namespace App\Services;

use App\Models\Cotizacion;
use App\Models\DetalleVenta;
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
        return Venta::with(['cliente', 'detalles','detalles.producto', 'abonos','abonos.operacion','abonos.operacion.cuenta'])->findOrFail($id);
    }

    public function crear(Cotizacion $data, $codigo): Venta
    {
        try {
            $venta = Venta::create([
                'fecha' => now("America/Lima")->format("Y-m-d"),
                'tipo_venta' => null,
                'estado' => "Pendiente",
                'codigo' => $codigo,
                'cliente_id' => $data->persona_id,
                'total' => $data->total,
                'abono_inicial' => 0.00,
                'saldo_pendiente' => $data->pendiente,
                'saldo_a_favor' => $data->favor,
                'comision_facturacion' => $data->facturacion,
                'envio' => $data->envio,
                'destino' => $data->destino,
                'nota' => "-",
                'subtotal' => $data->subtotal,
                'gasto_envio' => $data->encomienda,
                'utilidad'=>$data->utilidad
            ]);
            foreach ($data->productos as $value) {
                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $value->producto_id,
                    'imei' => "***",
                    'descripcion' => $value->registrado,
                    'precio_unitario' => $value->precio,
                    'cantidad' => $value->cantidad,
                    'subtotal' => number_format($value->precio * $value->cantidad, 2, '.', ''),
                ]);
            }
            return $venta;
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
