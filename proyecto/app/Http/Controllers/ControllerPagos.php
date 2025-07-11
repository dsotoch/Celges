<?php

namespace App\Http\Controllers;

use App\Services\ServicioAbonoVenta;
use App\Services\ServicioCompra;
use App\Services\ServicioCuentaBancaria;
use App\Services\ServicioCuentas;
use App\Services\ServicioOperacion;
use App\Services\ServicioPagos;
use App\Services\ServicioServicio;
use App\Services\ServicioVenta;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ControllerPagos extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $servicioCompras = new ServicioCompra();
        $servicioPagos = new ServicioPagos();
        $servicioServicios = new ServicioServicio();
        $servicioCuentasBancarias = new ServicioCuentaBancaria();
        $compras_pendientes = $servicioCompras->listarCuentasPendientes();
        $pagos = [];
        foreach ($compras_pendientes as $value) {
            $pagos[$value->id] = $servicioPagos->obtenerPagosCompra($value->id);
        }
        $compras = $compras_pendientes;
        $cuentasbancos = $servicioCuentasBancarias->listarActivas();
        $servicios = $servicioServicios->listar();
        $pendientes=$servicioPagos->listarPendientes();
        return view("pagos.index", compact("compras", "pagos", "cuentasbancos", 'servicios',"pendientes"));
    }

    /**
     * Show the form for creating a new resource.
     */

    public function createPagoServicio(Request $request)
    {
        try {
            DB::beginTransaction();
            $servicioPago = new ServicioPagos();
                // Crear pago
                $servicioPago->crear([
                    'persona_id'   => null,
                    'fecha_pago'   => $request->fecha,
                    'servicio_id'  => $request->servicio,
                    'metodo_pago'  => null,
                    'operacion_id' => null,
                    'monto_pagado' => $request->monto,
                    'nota'         => $request->nota,
                ]);
            
            DB::commit();
            return redirect()->back()->with(["success_servicio" => "✅ Pago Registrado Correctamente."]);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->withErrors(["error_servicio" => $th->getMessage()]);
        }
    }

    public function createPagoCompra(Request $request)
    {
        try {
            DB::beginTransaction();

            $servicioOperacion = new ServicioOperacion();
            $servicioPago = new ServicioPagos();
            $servicioCompra = new ServicioCompra();
            $montos = $request->monto ?? [];
            $tipos = $request->tipo ?? [];
            $numeros = $request->operac ?? [];
            $fechas = $request->fecha ?? [];
            $cuentas = $request->cuenta_id ?? [];

            foreach ($montos as $i => $monto) {
                if (empty($monto)) continue;

                // Crear operación
                $operacion = $servicioOperacion->crear([
                    'numero'     => $numeros[$i]     ?? null,
                    'tipo'       => $tipos[$i]       ?? null,
                    'fecha'      => $fechas[$i]      ?? now("America/Lima")->format("Y-m-d"),
                    'cuenta_id'  => $cuentas[$i]     ?? null,
                    'monto'      => $monto,
                ], "Compra");

                // Crear pago
                $servicioPago->crear([
                    'persona_id'   => $request->cliente_id,
                    'fecha_pago'   => now("America/Lima")->format("Y-m-d"),
                    'servicio_id'  => 2,
                    'metodo_pago'  => $tipos[$i] ?? null,
                    'operacion_id' => $operacion->id,
                    'monto_pagado' => $monto,
                    'nota'         => $request->compra_id,
                ]);
            }
            $servicioCompra->actualizar($request->compra_id, ["estado" => "pagado"]);

            DB::commit();
            return redirect()->back()->with(["success" => "✅ Pago Registrado Correctamente."]);
        } catch (\Exception $th) {
            DB::rollBack();
            return redirect()->back()->withErrors(["error" => $th->getMessage()]);
        }
    }

    public function GuardarPagoCompra(array $request, string $compra_id, string $personaId)
    {
        try {
            DB::beginTransaction();

            $servicioOperacion = new ServicioOperacion();
            $servicioPago = new ServicioPagos();
            $tipoCompra = $request['tipo_compra'] ?? '';
            $montos = $request['monto'] ?? [];
            $tipos = $request['tipo'] ?? [];
            $numeros = $request['operac'] ?? [];
            $fechas = $request['fecha'] ?? [];
            $cuentas = $request['cuenta_id'] ?? [];

            // Sumar total pagado
            $total = is_array($montos) && !empty($montos) ? array_sum($montos) : 0.00;

            // Solo pagos al contado
            if ($tipoCompra && $tipoCompra !== "credito") {
                foreach ($montos as $i => $monto) {
                    if (empty($monto)) continue;

                    // Crear operación
                    $operacion = $servicioOperacion->crear([
                        'numero'     => $numeros[$i]     ?? null,
                        'tipo'       => $tipos[$i]       ?? null,
                        'fecha'      => $fechas[$i]      ?? now("America/Lima")->format("Y-m-d"),
                        'cuenta_id'  => $cuentas[$i]     ?? null,
                        'monto'      => $monto,
                    ], "Compra");

                    // Crear pago
                    $servicioPago->crear([
                        'persona_id'   => $personaId,
                        'fecha_pago'   => now("America/Lima")->format("Y-m-d"),
                        'servicio_id'  => 1, // podrías hacerlo dinámico si varía
                        'metodo_pago'  => $tipos[$i] ?? null,
                        'operacion_id' => $operacion->id,
                        'monto_pagado' => $monto,
                        'nota'         => $compra_id,
                    ]);
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $th) {
            DB::rollBack();
            throw new \Exception($th->getMessage());
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $servicio = new ServicioAbonoVenta();
            $servicioOperacion = new ServicioOperacion();
            $servicioVenta = new ServicioVenta();

            $ventaId = $request->input('venta_id');
            $tipoVenta = $request->input('tipo_venta');
            $montos = $request->input('monto', []);
            $tipos = $request->input('tipo', []);

            $total = is_array($montos) && !empty($montos) ? array_sum($montos) : 0.00;
            if ($tipoVenta) {
                $venta = $servicioVenta->obtenerPorId($ventaId);
                $servicioVenta->actualizar($ventaId, [
                    "tipo_venta" => $tipoVenta,
                    "abono_inicial" => $total,
                    "saldo_pendiente" => max(0, $venta->total - $total),
                    "saldo_a_favor" => max(0, $total - $venta->total),
                    "estado" => $total >= $venta->total ? "Pagado" : "Deuda",
                ]);
                if ($tipoVenta != "Credito") {
                    foreach ($montos as $i => $monto) {
                        if (empty($monto)) continue;

                        $operacion = $servicioOperacion->crear([
                            'numero' => $request->input("numero.$i"),
                            'tipo' => $tipos[$i] ?? null,
                            'fecha' => $request->input("fecha.$i"),
                            'cuenta_id' => $request->input("cuenta_id.$i"),
                            'monto' => $monto,
                        ], "Venta");

                        $servicio->crear([
                            'venta_id' => $ventaId,
                            'fecha' => now("America/Lima")->format("Y-m-d"),
                            'monto' => $monto,
                            'metodo_pago' => $tipos[$i] ?? null,
                            'operacion_id' => $operacion->id,
                        ]);
                    }
                }

                $controladorCuentas = new ControllerCuentas();
                $controladorCuentas->store([
                    "venta_id" => $ventaId,
                    "total" =>  $venta->total,
                    "deuda" => max(0, $venta->total - $total),
                    "saldo_a_favor" => max(0, $total - $venta->total),
                    'cliente_id' => $venta->cliente_id
                ]);
            } else {
                $cliente_id = $request->input('cliente_id');

                $servicioCuentas = new ServicioCuentas();
                $cuentas = $servicioCuentas->obtenerPorId($cliente_id);

                // Ordenar cuentas por fecha o ID para pagar primero las más antiguas
                $ventas = $cuentas->pluck('venta')->sortBy('fecha');

                foreach ($montos as $i => $monto) {
                    if (empty($monto) || $monto <= 0) continue;

                    $montoRestante = $monto;

                    foreach ($ventas as $venta) {
                        // Obtener cuenta asociada
                        $cuenta = $cuentas->firstWhere('venta_id', $venta->id);
                        if (!$cuenta || $cuenta->deuda <= 0) continue;

                        // Monto a pagar en esta venta
                        $pagoAplicado = min($cuenta->deuda, $montoRestante);

                        // Crear operación de pago
                        $operacion = $servicioOperacion->crear([
                            'numero' => $request->input("numero.$i"),
                            'tipo' => $tipos[$i] ?? null,
                            'fecha' => $request->input("fecha.$i"),
                            'cuenta_id' => $request->input("cuenta_id.$i"),
                            'monto' => $pagoAplicado,
                        ], "Venta");

                        // Registrar el pago a esta venta
                        $servicio->crear([
                            'venta_id' => $venta->id,
                            'fecha' => now("America/Lima")->format("Y-m-d"),
                            'monto' => $pagoAplicado,
                            'metodo_pago' => $tipos[$i] ?? null,
                            'operacion_id' => $operacion->id,
                        ]);

                        // Actualizar la deuda de la cuenta
                        $cuenta->deuda -= $pagoAplicado;
                        $cuenta->save();

                        // Restar lo pagado del total disponible
                        $montoRestante -= $pagoAplicado;

                        // Si ya se usó todo el monto, salir del loop
                        if ($montoRestante <= 0) break;
                    }
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Pagos registrados correctamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            Log::error("Error SQL: " . $e->getMessage(), ['request' => $request->all()]);
            return redirect()->back()->withErrors(['error' => '❌ Error en la base de datos. Revisa los datos ingresados.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error general: " . $e->getMessage(), ['request' => $request->all()]);
            return redirect()->back()->withErrors(['error' => '❌ Error inesperado. Intenta nuevamente o contacta a soporte.']);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
