<?php

namespace App\Services;

use App\Models\Producto as ModelsProducto;
use Exception;
use Illuminate\Support\Facades\Log;

class ServicioProducto
{
    public function listar()
    {
        return ModelsProducto::with('persona')->get();
    }

    public function obtener(int $id): ModelsProducto
    {
        return ModelsProducto::with('persona')->findOrFail($id);
    }

    public function crear(array $data): ModelsProducto
    {
        try {
            return ModelsProducto::create($data);
        } catch (Exception $e) {
            Log::error("Error al crear producto: " . $e->getMessage());
            throw new Exception("No se pudo crear el producto.");
        }
    }

    public function actualizar(int $id, array $data): ModelsProducto
    {
        try {
            $producto = ModelsProducto::findOrFail($id);
            $producto->update($data);
            return $producto;
        } catch (Exception $e) {
            Log::error("Error al actualizar producto: " . $e->getMessage());
            throw new Exception("No se pudo actualizar el producto.");
        }
    }

    public function eliminar(int $id): bool
    {
        try {
            $producto = ModelsProducto::findOrFail($id);
            return $producto->delete();
        } catch (Exception $e) {
            Log::error("Error al eliminar producto: " . $e->getMessage());
            throw new Exception("No se pudo eliminar el producto.");
        }
    }
}
