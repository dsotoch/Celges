<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Services\ServicioAbonoVenta;
use App\Services\ServicioPagos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ControllerCaja extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $servicioAbonos = new ServicioAbonoVenta();
        $servicioPagos = new ServicioPagos();
        $abonos = $servicioAbonos->listarAbonosPorFecha(now("America/Lima")->format("Y-m-d"));
        $caja = Caja::where('estado', 'abierta')
            ->orderBy('fecha_apertura', 'desc')
            ->first();

        if (!$caja) {
            $caja = Caja::where('estado', 'cerrada')
                ->orderBy('fecha_cierre', 'desc')
                ->first();
        }

        $cajas = Caja::all();
        $abonosPorMetodoCuenta = [];

        foreach ($abonos as $abono) {
            $metodo = $abono->metodo_pago ?? 'Sin método';

            // Verifica que la relación exista
            $cuentaObj = $abono->operacion->cuenta ?? null;

            if ($cuentaObj) {
                $cuenta = "{$cuentaObj->tipo_cuenta} - {$cuentaObj->numero_cuenta}";
            } else {
                $cuenta = "Sin cuenta";
            }

            if (!isset($abonosPorMetodoCuenta[$metodo])) {
                $abonosPorMetodoCuenta[$metodo] = [];
            }

            if (!isset($abonosPorMetodoCuenta[$metodo][$cuenta])) {
                $abonosPorMetodoCuenta[$metodo][$cuenta] = 0;
            }

            $abonosPorMetodoCuenta[$metodo][$cuenta] += $abono->monto;
        }

        $pagos = $servicioPagos->listarPagosPorFecha(now("America/Lima")->format("Y-m-d"));
        $pagosAgrupados = [];

        foreach ($pagos as $pago) {
            $metodo = $pago->metodo_pago ?? 'Sin método';
            $servicio = $pago->servicio->nombre ?? 'Sin nombre';
            $monto = $pago->monto_pagado ?? 0;

            if (!isset($pagosAgrupados[$metodo])) {
                $pagosAgrupados[$metodo] = [];
            }

            if (!isset($pagosAgrupados[$metodo][$servicio])) {
                $pagosAgrupados[$metodo][$servicio] = 0;
            }

            $pagosAgrupados[$metodo][$servicio] += $monto;
        }

        $abonosEfectivo = [];

        if (isset($abonosPorMetodoCuenta['Efectivo'])) {
            foreach ($abonos as $abono) {
                if (($abono->metodo_pago ?? '') === 'Efectivo') {
                    $abonosEfectivo[] = $abono;
                }
            }
        }

        // Obtener todos los pagos con método "Efectivo"
        $pagosEfectivo = [];

        if (isset($pagosAgrupados['Efectivo'])) {
            foreach ($pagos as $pago) {
                if (($pago->metodo_pago ?? '') === 'Efectivo') {
                    $pago->nombre_persona = $pago->persona->nombres ?? 'Sin nombre';
                    $pagosEfectivo[] = $pago;
                }
            }
        }

        return view("caja.index", compact("cajas", "caja", "pagosEfectivo", "abonosEfectivo", "abonosPorMetodoCuenta", "pagosAgrupados"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createConfiguraciones(Request $request)
    {
        $request->validate([
            'telefono1' => 'required|digits:9',
            'telefono2' => 'nullable|digits:9',
        ]);

        try {
            DB::table('configuraciones')->updateOrInsert(
                ['id' => 1],
                [
                    'numero1' => $request->telefono1,
                    'numero2' => $request->telefono2,
                    'updated_at' => now()
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Teléfonos actualizados correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $cajaAbierta = Caja::where('estado', 'abierta')->first();
        if ($cajaAbierta) {
            return back()->with('error', 'Ya existe una caja abierta. Debes cerrarla antes de abrir una nueva.');
        }
        $cajaCerradaHoy = Caja::whereDate('fecha_cierre', now('America/Lima')->toDateString())->first();
        if ($cajaCerradaHoy) {
            return back()->with('error', 'Ya se cerró una caja hoy. No se puede aperturar otra hasta mañana.');
        }
        $caja = new Caja();
        $caja->usuario_id = Auth::user()->id;
        $caja->fecha_apertura = Carbon::now("America/Lima");
        $caja->monto_inicial = $request->monto_inicial;
        $caja->monto_final = $request->monto_inicial;
        $caja->estado = 'abierta';
        $caja->observacion = $request->observacion ?? null;
        $caja->save();

        return redirect()->back()->with('success', 'Caja aperturada correctamente.');
    }

    public function cerrar(Request $request)
    {
        $caja = Caja::where('estado', 'abierta')->first();

        if (!$caja) {
            return response()->json([
                'message' => 'No hay una caja abierta actualmente.'
            ], 400);
        }

        $caja->fecha_cierre = now('America/Lima');
        $caja->monto_final = $request->monto_final;
        $caja->estado = 'cerrada';
        $caja->save();

        return response()->json([
            'message' => 'Caja cerrada correctamente.',
            'monto_final' => number_format($caja->monto_final, 2)
        ]);
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
