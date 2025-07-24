<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\CuentaBancaria;
use App\Models\Persona;
use App\Models\Producto;
use App\Models\Venta;
use App\Services\ServicioAbonoVenta;
use App\Services\ServicioAlmacenInterno;
use App\Services\ServicioDetalleVentas;
use App\Services\ServicioPersona;
use App\Services\ServicioVenta;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $numeros = (array) DB::table('configuraciones')->select('numero1', 'numero2')->first();

        $max = Venta::max("id");
        $codigo = "VEN" . str_pad($max + 1, 4, "0", STR_PAD_LEFT);
        $cuentas = CuentaBancaria::where("activo", true)->get();
        $ventas_del_dia = Venta::where('fecha', Carbon::now("America/Lima")->format("Y-m-d"))
            ->where("estado", "!=", "Anulado")
            ->get();
        $ventas = Venta::orderByRaw("estado = 'Pendiente' DESC")
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        $cotizaciones = Cotizacion::where("estado", "Pendiente")
            ->orderBy("created_at", "desc")
            ->get();
        return view("ventas.index", compact("numeros", "ventas_del_dia", "ventas", "cotizaciones", "codigo", "codigopersona", "cuentas"));
    }

    /**
     * Show the form for creating a new resource.
     */

    private function restarDeuda($proveedorId, $monto, $ventaId, $codigoVenta)
    {
        try {
            $controladorPagos = new ControllerPagos();
            $controladorPagos->restarDeuda($proveedorId, $monto, $ventaId, $codigoVenta);
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage());
        }
    }
    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            $cotizacion = Cotizacion::with("productos")->findOrFail($request->cotizacion);
            $subtotal    = $request->subtotal ?? 0;
            $envio       = $request->envio ?? 0;
            $encomienda  = $request->encomienda ?? 0;
            $facturacion = $request->facturacion ?? 0;
            $favor       = $request->favor ?? 0;
            $saldoFavorUsado = $favor;

            $suma = $subtotal + $envio + $encomienda + $facturacion;
            if ($request->total == $suma) {
                $saldoFavorUsado = 0;
            }
            if ($saldoFavorUsado > 0) {
                if ($favor > $suma) {
                    $saldoFavorUsado = $suma;
                    $saldoFavorRestante = $favor - $suma;
                } else {
                    $saldoFavorUsado = $favor;
                    $saldoFavorRestante = 0;
                }
            }

            $cotizacion->update([
                'destino'     => $request->destino,
                'total'       => $request->total,
                'subtotal'    => $request->subtotal,
                'envio'       => $request->envio,
                'encomienda'  => $request->encomienda,
                'totalregistro'=> $request->totalregistro,
                'favor'       => $saldoFavorUsado,
                'pendiente'   => $request->pendiente,
                'facturacion' => $request->facturacion,
                'estado' => "Generado",
            ]);

            $servicio = new ServicioVenta();
            $serviciodetalleventa = new ServicioDetalleVentas();
            $ventaservicio = $servicio->crear($cotizacion);
            $codigo = "VEN" . str_pad($ventaservicio->id, 4, "0", STR_PAD_LEFT);
            $ventaservicio->codigo = $codigo;
            $ventaservicio->save();

            foreach ($cotizacion->productos as $value) {
                $serviciodetalleventa->crear([
                    'venta_id' => $ventaservicio->id,
                    'producto_id' => $value->producto_id,
                    'imei' => "***",
                    'descripcion' => $value->registrado,
                    'color'=>$value->color,
                    'precio_unitario' => $value->precio,
                    'cantidad' => $value->cantidad,
                    'subtotal' => $value->cantidad * $value->precio,
                ]);
            }
            if ($saldoFavorUsado > 0) {
                $this->restarDeuda(
                    $cotizacion->persona_id,
                    $saldoFavorUsado,
                    $ventaservicio->id,
                    $ventaservicio->codigo
                );
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
    public function update(string $id)
    {
        try {
            $servicio = new ServicioVenta();
            $venta = $servicio->actualizar((int)$id, [
                "estado" => "Despachado"
            ]);

            return redirect()->back()->with('success_edit', "✅ Se actualizó la venta {$venta->codigo} correctamente al estado 'Despachado'.");
        } catch (\Throwable $th) {
            return redirect()->back()->with('error_edit', "❌ Error al actualizar la venta: {$th->getMessage()}");
        }
    }

    public function anular(string $id)
    {
        try {
            DB::beginTransaction();
            $servicioVenta = new ServicioVenta();
            $servicioDetalleVenta = new ServicioDetalleVentas();
            $servicioAlmacen = new ServicioAlmacenInterno();
            $servicioAbono  = new ServicioAbonoVenta();

            $ventaSinModificar = $servicioVenta->obtenerPorId($id);
            if ($ventaSinModificar->estado == "Despachado") {
                $detalleVenta = $servicioDetalleVenta->obtenerPorIdTodaVenta($id);
                foreach ($detalleVenta as $value) {
                    $servicioAlmacen->actualizarPorImei($value->imei, ["cantidad" => $value->cantidad]);
                }
                $servicioAbono->eliminarAbonoVenta($id);
            }
            $venta = $servicioVenta->actualizar((int)$id, [
                "estado" => "Anulado"
            ]);
            DB::commit();
            return redirect()->back()->with('success_edit', "✅ Se Anuló la venta {$venta->codigo} correctamente.");
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error_edit', "❌ Error al anular la venta: {$th->getMessage()}");
        }
    }


    public function actualizarVentayProductos(Request $request)
    {

        try {
            DB::beginTransaction();
            $servicio = new ServicioVenta();
            $servicioDetalleVenta = new ServicioDetalleVentas();
            $servicioAlmacen = new ServicioAlmacenInterno();
            $venta = $servicio->actualizar((int)$request->numero_venta, [
                "estado" => $request->estado
            ]);
            $productos = $request->input('productos');
            foreach ($productos as $productoId => $datos) {
                $imeis = $datos['imeis'];
                $servicioDetalleVenta->actualizarPorProductoYImeis($productoId, $imeis, $request->numero_venta);
                $producto = Producto::find($productoId);
                $esTipoOtro = $producto && $producto->tipo === "OTRO";
                if (!$esTipoOtro) {
                    $servicioAlmacen->eliminarProductoporImei($imeis);
                }
            }
            DB::commit();
            return redirect()->back()->with(["success_edit" => "✅ Se Registro el nuevo Estado de la Venta $venta->codigo  Correctamente "]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("Error al registrar el nuevo estado de la venta: " . $th->getMessage());
            return redirect()->back()->withErrors([
                'error_edit' => '❌ Ocurrió un error al registrar el nuevo estado de la venta. Por favor, inténtalo nuevamente.'
            ]);
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
