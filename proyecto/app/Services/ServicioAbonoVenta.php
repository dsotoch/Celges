<?php

namespace App\Services;

use App\Models\AbonoVenta;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

class ServicioAbonoVenta
{

    public function listarTotalAbonosVenta(string $ventaId): float
    {
        try {
            $total = AbonoVenta::where('venta_id', $ventaId)
                ->where('validado', 'validado')
                ->sum('monto');

            return $total;
        } catch (\Throwable $th) {
            Log::error('Error al listar total de abonos: ' . $th->getMessage());
            throw new Exception('Error al obtener el total de abonos.');
        }
    }

    public function listarAbonoPendienteVenta(string $ventaId)
    {
        try {
            $total = AbonoVenta::where('venta_id', $ventaId)
                ->where('validado', 'no')
                ->count();

            return $total;
        } catch (\Throwable $th) {
            Log::error('Error al listar abonos pendientes: ' . $th->getMessage());
            throw new Exception('Error al obtener abonos pendientes');
        }
    }

    public function validarAbono(string $id)
    {
        try {
            $abono = AbonoVenta::findOrFail($id);
            $abono->validado = "validado";
            $abono->save();
        } catch (\Throwable $th) {

            throw new Exception($th->getMessage());
        }
    }

    public function listarAbonosDelMes()
    {
        $inicioMes = Carbon::now()->startOfMonth()->format('Y-m-d');
        $finMes = Carbon::now()->endOfMonth()->format('Y-m-d');

        return AbonoVenta::with([
            'venta',
            'operacion.cuenta'
        ])
            ->whereBetween('fecha', [$inicioMes, $finMes])
            ->whereHas('venta', function ($q) {
                $q->where('estado', '!=', 'Anulado');
            })
            ->get();
    }

    public function listarAbonosDeLaSemana()
    {
        $inicioSemana = Carbon::now()->startOfWeek()->format('Y-m-d');
        $finSemana = Carbon::now()->endOfWeek()->format('Y-m-d');

        return AbonoVenta::with([
            'venta',
            'operacion.cuenta'
        ])
            ->whereBetween('fecha', [$inicioSemana, $finSemana])
            ->whereHas('venta', function ($q) {
                $q->where('estado', '!=', 'Anulado');
            })
            ->get();
    }


    public function listarAbonosPorFecha($fecha)
    {
        $fechaFormateada = Carbon::parse($fecha)->format('Y-m-d');

        return AbonoVenta::with([
            'venta',
            'operacion.cuenta',
            'venta.cliente'
        ])
            ->whereDate('fecha', $fechaFormateada)
            ->get();
    }
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

    public function eliminarAbonoVenta(int $venta_id): bool
    {
        try {
            $abono = AbonoVenta::where("venta_id", $venta_id)->get();
            foreach ($abono as $it) {
                $it->delete();
            }
            return true;
        } catch (Exception $e) {
            Log::error("Error al eliminar abono de venta: " . $e->getMessage());
            throw new Exception("No se pudo eliminar el abono.");
        }
    }
}
