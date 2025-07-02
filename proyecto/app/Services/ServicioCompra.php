<?php

namespace App\Services;

use App\Models\Compra;
use Exception;
use Illuminate\Support\Facades\Log;

class ServicioCompra
{
    public function listar()
    {
        return Compra::with('persona')->get();
    }

    public function obtenerPorId(int $id): Compra
    {
        return Compra::with('persona')->findOrFail($id);
    }

    public function crear(array $data): Compra
    {
        try {

            return Compra::create($data);
        } catch (Exception $e) {
            Log::error("Error al registrar el detalle de la compra: " . $e->getMessage());
            throw new Exception("No se pudo registrar la compra.");
        }
    }
   
    public function actualizar(int $id, array $data): Compra
    {
        try {
            $compra = Compra::findOrFail($id);
            $compra->update($data);
            return $compra;
        } catch (Exception $e) {
            Log::error("Error al actualizar la compra: " . $e->getMessage());
            throw new Exception("No se pudo actualizar la compra.");
        }
    }

    public function eliminar(int $id): bool
    {
        try {
            $compra = Compra::findOrFail($id);
            $compra->delete();
            return true;
        } catch (Exception $e) {
            Log::error("Error al eliminar la compra: " . $e->getMessage());
            throw new Exception("No se pudo eliminar la compra.");
        }
    }
    public function obtenerCodigo(int $numeroActual): string
    {
        return str_pad((string) $numeroActual, 4, '0', STR_PAD_LEFT);
    }
}
