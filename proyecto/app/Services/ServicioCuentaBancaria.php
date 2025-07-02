<?php

namespace App\Services;

use App\Models\CuentaBancaria;
use Exception;
use Illuminate\Support\Facades\Log;

class ServicioCuentaBancaria
{
    public function listar()
    {
        return CuentaBancaria::all();
    }

    public function obtenerPorId(int $id): CuentaBancaria
    {
        return CuentaBancaria::findOrFail($id);
    }

    public function crear(array $data): CuentaBancaria
    {
        try {
            $banco          = $data['banco'] ?? null;
            $tipo_cuenta    = $data['tipo_cuenta'] ?? null;
            $numero_cuenta  = $data['numero_cuenta'] ?? null;
            $cci            = $data['cci'] ?? null;
            $moneda         = $data['moneda'] ?? null;
            $empresa        = $data['banco'] ?? null;
            $titular        = $data['titular'] ?? null;
            $activo         = $data['activo'] ?? true;

            if ($tipo_cuenta == "CCI") {
                $cci = $numero_cuenta;
                $numero_cuenta = "-";
            }
            return CuentaBancaria::create([
                'banco'          => $banco,
                'tipo_cuenta'    => $tipo_cuenta,
                'numero_cuenta'  => $numero_cuenta,
                'cci'            => $cci,
                'moneda'         => $moneda,
                'empresa'        => 0,
                'titular'        => $titular,
                'activo'         => $activo,
            ]);
        } catch (Exception $e) {
            Log::error("Error al crear cuenta bancaria: " . $e->getMessage());
            throw new Exception("No se pudo crear la cuenta bancaria.");
        }
    }

    public function actualizar(int $id, array $data): CuentaBancaria
    {
        try {
            $cuenta = CuentaBancaria::findOrFail($id);
            $cuenta->update($data);
            return $cuenta;
        } catch (Exception $e) {
            Log::error("Error al actualizar cuenta bancaria: " . $e->getMessage());
            throw new Exception("No se pudo actualizar la cuenta bancaria.");
        }
    }

    public function eliminar(int $id): bool
    {
        try {
            $cuenta = CuentaBancaria::findOrFail($id);
            $cuenta->delete();
            return true;
        } catch (Exception $e) {
            Log::error("Error al eliminar cuenta bancaria: " . $e->getMessage());
            throw new Exception("No se pudo eliminar la cuenta bancaria.");
        }
    }
}
