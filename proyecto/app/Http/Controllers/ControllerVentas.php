<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\CuentaBancaria;
use App\Models\DetalleVenta;
use App\Models\Persona;
use App\Models\Producto;
use App\Models\Venta;
use App\Services\ServicioAbonoVenta;
use App\Services\ServicioAlmacenInterno;
use App\Services\ServicioDetalleVentas;
use App\Services\ServicioPersona;
use App\Services\ServicioVenta;
use Barryvdh\DomPDF\Facade\Pdf;
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

    public function pdf(string $id)
    {
        $venta = Venta::find($id);
        $numeros = DB::table('configuraciones')
            ->select('numero1', 'numero2')
            ->first();

        $pdf = Pdf::setOption(['isRemoteEnabled' => true])->loadView('pdf.invoice', compact('venta', 'numeros'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('Invoice_' . $venta->id . '.pdf');
    }

    public function eliminarproducto($venta)
    {
        $detalleVenta = DetalleVenta::where("id", $venta)->first();

        if (!$detalleVenta) {
            return response()->json(['error' => 'Producto no encontrado'], 404);
        }
        $subtotal = $detalleVenta->subtotal;
        $idventa = $detalleVenta->venta_id;
        $detalleVenta->delete();

        Venta::find($idventa)->decrement('subtotal', $subtotal);

        $ventas = Venta::find($idventa);


        $nota = $ventas->nota ?? "";
        $facturacion = floatval($ventas->comision_facturacion ?? 0);
        $envio = floatval($ventas->envio ?? 0);
        $encomienda = floatval($ventas->encomienda ?? 0);
        $favor = floatval($ventas->favor ?? 0);
        $gastoenvio = floatval($ventas->gasto_envio ?? 0);
        $totalregistro = floatval($ventas->totalregistro ?? 0);

        $total = $ventas->subtotal + $envio + $encomienda + $gastoenvio + $totalregistro + $facturacion;

        if ($nota != "-") {
            $total -= $favor;
        }

        $venta2 = Venta::with('abonos')->findOrFail($idventa);

        $totalAbonos = $venta2->abonos->sum('monto');

        $ventas->update([
            'total' => $total,
            'saldo_pendiente' => max(0, $total - $totalAbonos),
        ]);
        return response()->json(['success' => 'Producto eliminado correctamente'], 200);
    }
    public function actualizarpreciosProductos($venta, $id, $cantidad)
    {
        // Obtener el detalle de la venta
        $detalleVenta = DetalleVenta::find($venta);

        if (!$detalleVenta) {
            return response()->json(['error' => 'Producto no encontrado'], 404);
        }

        // Actualizar el subtotal del producto según la nueva cantidad
        $nuevoSubtotal = $detalleVenta->precio_unitario * $cantidad;
        $detalleVenta->update([
            "cantidad" => $cantidad,
            "subtotal" => $nuevoSubtotal,
        ]);

        // Recalcular el subtotal general de la venta
        $subtotalGeneral = DetalleVenta::where("venta_id", $detalleVenta->venta_id)->sum("subtotal");

        // Obtener la venta
        $venta = Venta::find($detalleVenta->venta_id);

        if (!$venta) {
            return response()->json(['error' => 'Venta no encontrada'], 404);
        }



        $facturacion = floatval($venta->comision_facturacion ?? 0);
        $nota = $venta->nota ?? "";
        $envio = floatval($venta->envio ?? 0);
        $encomienda = floatval($venta->encomienda ?? 0);
        $favor = floatval($venta->favor ?? 0);
        $gastoenvio = floatval($venta->gasto_envio ?? 0);
        $totalregistro = floatval($venta->total_registro ?? 0);

        $total = $subtotalGeneral + $envio + $encomienda + $gastoenvio + $totalregistro;

        $porcentaje = 0;
        $nuevofacturacion = 0;
        if ($facturacion > 0) {
            $porcentaje = ($venta->total / $facturacion) * 100;
            $nuevofacturacion = $total * $porcentaje;
            $total += $nuevofacturacion;
        }

        if ($nota != "-") {
            $total -= $favor;
        }

        // Obtener la venta con sus abonos
        $venta2 = Venta::with('abonos')->findOrFail($venta->id);

        // Sumar todos los abonos registrados de esa venta
        $totalAbonos = $venta2->abonos->sum('monto');

        // Actualizar los valores de la venta
        $venta->update([
            'subtotal' => $subtotalGeneral,
            'total' => $total,
            'comision_facturacion' => $nuevofacturacion,
            'saldo_pendiente' => max(0, $total - $totalAbonos),
        ]);


        return response()->json([
            'success' => 'Datos actualizados correctamente',

        ], 200);
    }

    public function actualizarprecioporinput($venta, $input, $cantidad)
    {



        $subtotalGeneral = DetalleVenta::where("venta_id", $venta)->sum("subtotal");

        // Obtener la venta
        $ventas = Venta::find($venta);

        if (!$ventas) {
            return response()->json(['error' => 'Venta no encontrada'], 404);
        }



        $nota = $ventas->nota ?? "";
        $facturacion = floatval($ventas->comision_facturacion ?? 0);

        $envio = floatval($ventas->envio ?? 0);
        $encomienda = floatval($ventas->encomienda ?? 0);
        $favor = floatval($ventas->favor ?? 0);
        $gastoenvio = floatval($ventas->gasto_envio ?? 0);
        $totalregistro = floatval($ventas->totalregistro ?? 0);



        switch ($input) {
            case 'envio':
                $total = $subtotalGeneral + $cantidad + $encomienda + $gastoenvio + $totalregistro + $facturacion;
                $ventas->update([
                    'envio' => $cantidad,
                ]);
                break;
            case 'encomienda':
                $total = $subtotalGeneral + $envio + $cantidad + $gastoenvio + $totalregistro + $facturacion;
                $ventas->update([
                    'gasto_envio' => $cantidad,
                ]);
                break;
            case 'facturacion':
                $total = $subtotalGeneral + $envio + $encomienda + $gastoenvio + $totalregistro + $cantidad;
                $ventas->update([
                    'comision_facturacion' => $cantidad,
                ]);
                break;
            case 'totalregistro':
                $total = $subtotalGeneral + $envio + $encomienda + $gastoenvio + $cantidad + $facturacion;
                $ventas->update([
                    'totalregistro' => $cantidad,
                ]);
                break;
        }


        if ($nota != "-") {
            $total -= $favor;
        }

        $venta2 = Venta::with('abonos')->findOrFail($venta);

        $totalAbonos = $venta2->abonos->sum('monto');

        $ventas->update([
            'total' => $total,
            'saldo_pendiente' => max(0, $total - $totalAbonos),
        ]);


        return response()->json([
            'success' => 'Datos actualizados correctamente',

        ], 200);
    }

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
    public function create(string $id)
    {
        try {
            DB::beginTransaction();
            $cotizacion = Cotizacion::with("productos")->findOrFail($id);
            $subtotal    = $cotizacion->subtotal ?? 0;
            $envio       = $cotizacion->envio ?? 0;
            $encomienda  = $cotizacion->encomienda ?? 0;
            $facturacion = $cotizacion->facturacion ?? 0;
            $favor       = $cotizacion->favor ?? 0;
            $saldoFavorUsado = $favor;

            $suma = $subtotal + $envio + $encomienda + $facturacion;
            if ($cotizacion->total == $suma) {
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
                'destino'     => $cotizacion->destino,
                'total'       => $cotizacion->total,
                'subtotal'    => $cotizacion->subtotal,
                'envio'       => $cotizacion->envio,
                'encomienda'  => $cotizacion->encomienda,
                'totalregistro' => $cotizacion->totalregistro,
                'favor'       => $saldoFavorUsado,
                'pendiente'   => $cotizacion->pendiente,
                'facturacion' => $cotizacion->facturacion,
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
                    'color' => $value->color,
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
            return $ventaservicio->id;
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
        $venta= Venta::find($id);
        $venta->delete();
                    return redirect()->back()->with(["success_edit" => "✅ Se Elimino la venta  Correctamente "]);

    }
}
