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
            return CuentaBancaria::create($data);
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
