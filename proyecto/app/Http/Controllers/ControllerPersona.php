<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditPersonaRequest;
use App\Http\Requests\StorePersonaRequest;
use App\Models\Cotizacion;
use App\Models\Persona;
use App\Models\Tipo;
use App\Services\ServicioPersona;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function create(String $id, Request $request)
    {
        try {
            DB::beginTransaction();
            // Validar si ya existe el teléfono
            $existe = Persona::where("telefono", $request->telefono)->first();
            if ($existe) {
                throw new Exception("💢 El teléfono ya está registrado.");
            }

            // Validar si ya existe el email
            $existe2 = Persona::where("email", $request->email)->first();
            if ($existe2) {
                throw new Exception("💢 El correo electrónico ya está registrado.");
            }

            // Crear la persona
            $servicio = new ServicioPersona();
            $persona = $servicio->crear($request->all());
            Cotizacion::where("id", $id)
                ->update(["persona_id" => $persona->id, "cliente" => $persona->nombres]);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Proveedor registrado correctamente.',
                'id' => $persona->id
            ], 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage()
            ], 500);
        }
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
