<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequestCotizacion;
use App\Models\AlmacenInterno;
use App\Models\Cotizacion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ControllerCotizaciones extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $maxId = Cotizacion::max("id") ?? 0;
        $codigo = "COT" . str_pad($maxId + 1, 4, "0", STR_PAD_LEFT);
        $cotizaciones = Cotizacion::all();
        $almacen = AlmacenInterno::with("compra", "producto")->get();
        return view("cotizacion.index", compact("cotizaciones", "codigo", "almacen"));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequestCotizacion $request)
    {
        try {
            DB::beginTransaction();
            // Crear cotización
            $cotizacion = Cotizacion::create([
                'cliente' => $request->cliente,
                'codigo' => $request->codigo,
                'destino' => $request->destino,
                'subtotal' => $request->subtotal,
                'total' => $request->total,
                'utilidad' => $request->utilidad ?? 0,
                'envio' => $request->envio ?? 0,
                'encomienda' => $request->encomienda ?? 0,
                'facturacion' => $request->facturacion ?? 0,
                'favor' => $request->favor ?? 0,
                'pendiente' => $request->pendiente ?? 0,
                'created_at' => Carbon::now("America/Lima")->format("Y-m-d")
            ]);

            if (!empty($request->cliente_id)) {
                $cotizacion->cliente()->associate($request->cliente_id);
            }
            $cotizacion->save();

            $cotizacion->productos()->createMany(
                collect($request->productos)->map(function ($producto) {
                    return [
                        'producto_id' => $producto['id'],
                        'cantidad' => $producto['cantidad'],
                        'precio' => $producto['precio'],
                        'registrado' => $producto['registrado'],
                    ];
                })->toArray()
            );

            DB::commit();
            return response()->json([
                'message' => 'Cotización guardada correctamente',
                'cotizacion_id' => $cotizacion->id
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al guardar la cotización: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $servicio = Cotizacion::with("productos", "productos.producto")->findOr($id);
        return response()->json($servicio);
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
    public function update(string $id)
    {
        try {
            $cotizacion = Cotizacion::findOrFail($id);
            $cotizacion->update(["estado" => "Anulado"]);

            return redirect()->back()->with("success", "Se anuló la Cotizacion " . $cotizacion->codigo . " correctamente.");
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => $th->getMessage()]);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
