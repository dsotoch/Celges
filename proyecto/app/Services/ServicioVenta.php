<?php

namespace App\Services;

use App\Models\Cotizacion;
use App\Models\DetalleVenta;
use App\Models\Venta;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServicioVenta
{
    public function ventasAprobacionPendiente()
    {

        return Venta::with([
            'abonos' => function ($query) {
                $query->where('metodo_pago', '!=', 'Efectivo');
            },
            'abonos.operacion'
        ])
            ->where("estado", "Esperando Aprobacion")
            ->whereHas('abonos', function ($query) {
                $query->where('metodo_pago', '!=', 'Efectivo');
            })
            ->get();
    }

    public function calcularTicketPromedioAnual()
    {
        $ventas = Venta::whereYear('fecha', now()->year)->where("estado", "!=", "Anulado");

        $totalVendido = $ventas->sum('total');
        $numeroVentas = $ventas->count();

        $ticketPromedio = $numeroVentas > 0 ? $totalVendido / $numeroVentas : 0;

        return [
            'total_vendido' => $totalVendido,
            'numero_ventas' => $numeroVentas,
            'ticket_promedio' => round($ticketPromedio, 2),
        ];
    }
   public function listarActivas()
    {
        return Venta::with(['cliente', 'detalles', 'abonos'])->where("estado", "!=", "Anulada")->get();
    }


    public function calcularTicketPromedioMes()
    {
        $ventas = Venta::whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)->where("estado", "!=", "Anulado");

        $totalVendido = $ventas->sum('total');
        $numeroVentas = $ventas->count();

        $ticketPromedio = $numeroVentas > 0 ? $totalVendido / $numeroVentas : 0;

        return [
            'total_vendido' => $totalVendido,
            'numero_ventas' => $numeroVentas,
            'ticket_promedio' => round($ticketPromedio, 2),
        ];
    }
    public function obtenerClientesTop()
    {

        $clientesTop = Venta::select('cliente_id', DB::raw('SUM(total) as total_comprado'))
            ->whereMonth('fecha', now()->format('m'))
            ->whereYear('fecha', now()->format('Y'))
            ->groupBy('cliente_id')
            ->orderByDesc('total_comprado')
            ->with('cliente')
            ->take(3)
            ->get();

        return $clientesTop->map(function ($venta) {
            return [
                'cliente' => $venta->cliente,
                'total_comprado' => (float) $venta->total_comprado,
            ];
        });
    }


    public function listarPorfecha($fecha)
    {
        try {
            $fechaFormateada = Carbon::parse($fecha)->format('Y-m-d');

            return Venta::with(['cliente', 'detalles', 'abonos', 'abonos.operacion', 'abonos.operacion.cuenta'])
                ->whereDate('fecha', $fechaFormateada)
                ->get();
        } catch (\Exception $e) {
            Log::error("Error al listar ventas por fecha: " . $e->getMessage());
            return collect();
        }
    }

    public function listar()
    {
        return Venta::with(['cliente', 'detalles', 'abonos'])->get();
    }

    public function obtenerPorId(int $id): Venta
    {
        return Venta::with(['cliente', 'detalles', 'detalles.producto', 'abonos', 'abonos.operacion', 'abonos.operacion.cuenta'])->findOrFail($id);
    }

    public function crear(Cotizacion $data): Venta
    {
        try {
            $venta = Venta::create([
                'fecha' => now("America/Lima")->format("Y-m-d"),
                'tipo_venta' => null,
                'estado' => "Pendiente",
                'codigo' => "-",
                'cliente_id' => $data->persona_id,
                'total' => $data->total,
                'abono_inicial' => 0.00,
                'saldo_pendiente' => $data->pendiente,
                'saldo_a_favor' => $data->favor,
                'totalregistro'=>$data->totalregistro,
                'notaProductos'=>"-",
                'color'=>$data->color,
                'comision_facturacion' => $data->facturacion,
                'envio' => $data->envio,
                'destino' => $data->destino,
                'nota' => "-",
                'subtotal' => $data->subtotal,
                'gasto_envio' => $data->encomienda,
                'utilidad' => $data->utilidad
            ]);
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
