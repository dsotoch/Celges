<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\CuentaBancaria;
use App\Models\Persona;
use App\Models\Venta;
use App\Services\ServicioDetalleVentas;
use App\Services\ServicioPersona;
use App\Services\ServicioVenta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ControllerVentas extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $servicio = new ServicioPersona();
        $personas = Persona::max("id") + 1;
        $codigopersona = "PE" . $servicio->obtenerCodigo($personas);

        $max = Venta::max("id");
        $codigo = "VEN" . str_pad($max + 1, 4, "0", STR_PAD_LEFT);
        $cuentas = CuentaBancaria::where("activo", true)->get();
        $ventas_del_dia = Venta::where('fecha', Carbon::now("America/Lima")->format("Y-m-d"));
        $ventas = Venta::paginate(50);
        $cotizaciones = Cotizacion::where("estado", "Pendiente")->get();
        return view("ventas.index", compact("ventas_del_dia", "ventas", "cotizaciones", "codigo", "codigopersona", "cuentas"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            $cotizacion = Cotizacion::with("productos")->findOrFail($request->cotizacion);
            $cotizacion->update([
                'destino'     => $request->destino,
                'total'       => $request->total,
                'subtotal'    => $request->subtotal,
                'envio'       => $request->envio,
                'encomienda'  => $request->encomienda,
                'favor'       => $request->favor,
                'pendiente'   => $request->pendiente,
                'facturacion' => $request->facturacion,
                'estado' => "Generado",
            ]);
            $max = Venta::max("id");
            $codigo = "VEN" . str_pad($max + 1, 4, "0", STR_PAD_LEFT);
            $servicio = new ServicioVenta();
            $serviciodetalleventa = new ServicioDetalleVentas();
            $ventaservicio=$servicio->crear($cotizacion, $codigo);
            foreach ($cotizacion->productos as $value) {
                $serviciodetalleventa->crear([
                    'venta_id' => $ventaservicio->id,
                    'producto_id' => $value->producto_id,
                    'imei' => "***",
                    'descripcion' => $value->registrado,
                    'precio_unitario' => $value->precio,
                    'cantidad' => $value->cantidad,
                    'subtotal' => $value->cantidad * $value->precio,
                ]);
            }

            DB::commit();
            return response()->json(["success" => true, "mensaje" => "Venta Registrada Correctamente"], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(["success" => false, "mensaje" => $th], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $servicio = new ServicioVenta();
            $venta = $servicio->obtenerPorId((int)$id); // cast por seguridad

            return response()->json([
                'success' => true,
                'data' => $venta
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la venta',
                'error' => $th->getMessage()
            ], 500);
        }
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
