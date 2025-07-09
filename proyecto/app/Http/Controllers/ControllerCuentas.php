<?php

namespace App\Http\Controllers;

use App\Models\CuentaBancaria;
use App\Models\Cuentas;
use App\Models\Venta;
use App\Services\ServicioCuentas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ControllerCuentas extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $servicio=new ServicioCuentas();
        $cuentas = $servicio->listar();
        $cuentasbancos = CuentaBancaria::all();
        return view("cuentas.index", compact("cuentas", "cuentasbancos"));
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
        $servicio=new ServicioCuentas();
        $cuentas = $servicio->detallesCuentas($id);
        return response()->json($cuentas);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {}

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
