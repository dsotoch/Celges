<?php

namespace App\Http\Controllers;

use App\Models\AlmacenInterno;
use App\Services\ServicioAlmacenInterno;
use App\Services\ServicioVenta;
use Illuminate\Http\Request;

class ControllerAlmacenInterno extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $servicio = new ServicioAlmacenInterno();
        $almaceninterno = $servicio->listar();

        return view("almaceninterno.index", compact("almaceninterno"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function listarProductosPorId($id)
    {
        $servicioVenta = new ServicioVenta();
        $servicioAlmacen = new ServicioAlmacenInterno();

        $venta = $servicioVenta->obtenerPorId($id);
        $agrupados = [];

        foreach ($venta->detalles as $detalle) {
            $productoId = $detalle->producto_id;
            $descripcion = trim($detalle->descripcion);
            $productonombre = $detalle->producto->marca . " " . $detalle->producto->modelo . " " . $detalle->producto->capacidad;

            // Clave única para agrupación
            $clave = $productoId . '||' . $descripcion;

            if (!isset($agrupados[$clave])) {
                // Buscar registros de almacén con ese producto y descripción
                $almacenes = $servicioAlmacen->buscarPorProductoYDescripcion($productoId, $descripcion);

                // Transformar cada registro en unidades individuales
                $almacenesUnitarios = [];
                foreach ($almacenes as $almacen) {
                    for ($i = 0; $i < $almacen->cantidad; $i++) {
                        $almacenesUnitarios[] = [
                            'id' => $almacen->id,
                            'imei' => $almacen->imei,
                            'registrado' => $almacen->registrado,
                            'compra' => $almacen->compra,
                            'producto' => $almacen->producto,
                            'color' => $almacen->color,
                        ];
                    }
                }

                $agrupados[$clave] = [
                    'producto_id' => $productoId,
                    'producto_nombre' => $productonombre,
                    'descripcion' => $descripcion,
                    'cantidad' => $detalle->cantidad,
                    'almacen' => $almacenesUnitarios,
                ];
            } else {
                $agrupados[$clave]['cantidad'] += $detalle->cantidad;
            }
        }

        return response()->json(array_values($agrupados));
    }






    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $idsArray = explode(',', $id);

        $items = AlmacenInterno::with(['compra', 'producto'])
            ->whereIn('id', $idsArray)
            ->get();
        $almaceninterno = $items;
        return view("almaceninterno.detalles", compact("almaceninterno"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
