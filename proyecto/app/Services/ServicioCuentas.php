<?php

namespace App\Services;

use App\Models\Cuentas;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServicioCuentas
{
    public function crear(array $data): Cuentas
    {
        try {
            return Cuentas::create($data);
        } catch (Exception $e) {
            Log::error("Error al crear Cuentas: " . $e->getMessage());
            throw new Exception("No se pudo crear la Cuentas.");
        }
    }

    public function actualizar(int $id, array $data): Cuentas
    {
        try {
            $Cuentas = Cuentas::findOrFail($id);
            $Cuentas->update($data);
            return $Cuentas;
        } catch (Exception $e) {
            Log::error("Error al actualizar Cuentas: " . $e->getMessage());
            throw new Exception("No se pudo actualizar la Cuentas.");
        }
    }

    public function eliminar(int $id): bool
    {
        try {
            $Cuentas = Cuentas::findOrFail($id);
            return $Cuentas->delete();
        } catch (Exception $e) {
            Log::error("Error al eliminar Cuentas: " . $e->getMessage());
            throw new Exception("No se pudo eliminar la Cuentas.");
        }
    }

    public function obtenerPorId(int $id)
    {
        return Cuentas::with("venta")->whereRelation("venta", "cliente_id", "=", $id)->get();
    }

    public function listar()
    {
        return Cuentas::join('ventas', 'cuentas.venta_id', '=', 'ventas.id')
            ->join('personas', 'ventas.cliente_id', '=', 'personas.id')
            ->select(
                'personas.id as cliente_id',
                'personas.telefono',
                'personas.nombres as nombres',
                DB::raw('SUM(cuentas.deuda) as total_deuda')
            )
            ->where("cuentas.deuda",">","0")
            ->groupBy('personas.id', 'personas.nombres', 'personas.telefono')
            ->get();
    }

    public function detallesCuentas(string $id)
    {
        return Cuentas::with("venta","venta.abonos","venta.abonos.operacion")->whereRelation("venta", "cliente_id", "=", $id)->get();
    }

    public function obtenerCodigo(int $numeroActual): string
    {
        return str_pad((string) $numeroActual, 4, '0', STR_PAD_LEFT);
    }
}
