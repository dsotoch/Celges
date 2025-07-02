<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBancosRequest;
use App\Models\CuentaBancaria;
use App\Services\ServicioCuentaBancaria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ControllerCuentaBancaria extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cuentas = CuentaBancaria::all();
        return view("bancos.index", compact("cuentas"));
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
    public function store(StoreBancosRequest $request)
    {
        try {
            $servicio = new ServicioCuentaBancaria();
            $servicio->crear($request->validated());
            return redirect()->back()->with('success', '✅ Cuenta bancaria registrada correctamente.');
        } catch (\Throwable $th) {
            Log::error('Error al registrar cuenta bancaria: ' . $th->getMessage());
            return redirect()->back()
                ->with('error', '⚠️ Ocurrió un error al registrar la cuenta bancaria. Verifica los datos o contacta al administrador.')
                ->withInput();
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
        try {
            $servicio = new ServicioCuentaBancaria();
            $servicio->actualizar($id, $request->validate(["activo" => "required"]));
            return redirect()->back()->with('success', '✅ Cuenta Modificada correctamente.');
        } catch (\Throwable $th) {
            Log::error('Error al Modificar cuenta bancaria: ' . $th->getMessage());
            return redirect()->back()
                ->with('error', '⚠️ Ocurrió un error al Modificar la cuenta bancaria. Verifica los datos o contacta al administrador.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $servicio = new ServicioCuentaBancaria();
            $servicio->eliminar($id);
            return redirect()->back()->with('success', '✅ Cuenta Eliminada correctamente.');
        } catch (\Throwable $th) {
            Log::error('Error al Eliminar cuenta bancaria: ' . $th->getMessage());
            return redirect()->back()
                ->with('error', '⚠️ Ocurrió un error al Eliminar la cuenta bancaria. Verifica los datos o contacta al administrador.')
                ->withInput();
        }
    }
}
