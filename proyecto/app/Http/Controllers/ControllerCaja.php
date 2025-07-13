<?php

namespace App\Http\Controllers;

use App\Services\ServicioAbonoVenta;
use App\Services\ServicioPagos;
use App\Services\ServicioVenta;
use Illuminate\Http\Request;

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

        return view("caja.index", compact("abonosPorMetodoCuenta","pagosAgrupados"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
