<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditPersonaRequest;
use App\Http\Requests\StorePersonaRequest;
use App\Models\Persona;
use App\Models\Tipo;
use App\Services\ServicioPersona;
use Illuminate\Http\Request;

class ControllerPersona extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipos = Tipo::all();
        $servicio = new ServicioPersona();
        $personas = $servicio->listar();
        $codigo = $this->obtenerCodigo();
        return view('proveedores.index', compact('personas', 'codigo', 'tipos'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePersonaRequest $request)
    {
        try {
            $servicio = new ServicioPersona();
            $servicio->crear($request->validated());
            return redirect()->route('proveedores.index')
                ->with('success', 'Proveedor registrado correctamente.');
        } catch (\Exception $ex) {
            return redirect()->back()
                ->withErrors(['general' => $ex->getMessage()])
                ->withInput()
                ->with('show_modal', true);
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
     * Update the specified resource in storage.
     */
    public function update(EditPersonaRequest $request, string $id)
    {
        try {
            $servicio = new ServicioPersona();
            $servicio->actualizar($id, $request->validated());
            return redirect()->route('proveedores.index')
                ->with('success_edit', 'Proveedor modificado correctamente.');
        } catch (\Exception $ex) {
            return redirect()->back()
                ->withErrors(['general_edit' => $ex->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $servicio = new ServicioPersona();
            $servicio->eliminar($id);
            return redirect()->route('proveedores.index')
                ->with('success-delete', 'Proveedor eliminado correctamente.');
        } catch (\Exception $ex) {
            return redirect()->back()
                ->withErrors(['general-error' => $ex->getMessage()])
                ->withInput()
                ->with('show_modal', true);
        }
    }

    private function obtenerCodigo(): string
    {
        $servicio = new ServicioPersona();
        $personas = Persona::max("id") + 1;
        return "PE" . $servicio->obtenerCodigo($personas);
    }
}
