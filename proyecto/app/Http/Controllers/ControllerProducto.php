<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditProductoRequest;
use App\Http\Requests\StoreProductoRequest;
use App\Models\Capacidades;
use App\Models\Marcas;
use App\Models\Producto;
use App\Services\ServicioProducto;
use Exception;
use Illuminate\Http\Request;

class ControllerProducto extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function guardarMarca(Request $request)
    {
        if (!empty($request->marca)) {
            foreach ($request->marca as $value) {
                Marcas::create([
                    "marca" => $value
                ]);
            }

            return redirect()->back()->with([
                "success-atributo" => "Marca(s) registrada(s) correctamente."
            ]);
        } else {
            return redirect()->back()->withErrors([
                "error-atributo" => "Debe ingresar al menos una marca para registrar."
            ]);
        }
    }



    public function guardarCapacidad(Request $request)
    {
        if (!empty($request->capacidad)) {
            foreach ($request->capacidad as $value) {
                Capacidades::create([
                    'capacidad' => $value
                ]);
            }

            return redirect()->back()->with([
                'success-atributo' => 'Capacidad(es) registrada(s) correctamente.'
            ]);
        } else {
            return redirect()->back()->withErrors([
                'error-atributo' => 'Debe ingresar al menos una capacidad para registrar.'
            ]);
        }
    }
    public function index()
    {
        $productos = Producto::paginate(50);

        $servicio = new ServicioProducto();
        $codigo =  "PR-" . $servicio->obtenerCodigo(Producto::max("id") + 1);
        $marcas = Marcas::all();
        $capacidades = Capacidades::all();
        return view('producto.index', compact('capacidades', 'marcas', 'productos', 'codigo'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductoRequest $request)
    {
        try {
            $datos = array_map('mb_strtoupper', $request->validated());

            $servicio = new ServicioProducto();
            $servicio->crear($datos);
            return redirect()->route("productos.index")
                ->with(["success" => "Producto Registrado Correctamente."]);
        } catch (Exception $e) {
            return redirect()->back()->withErrors(
                ["general" => $e->getMessage()]
            )->withInput()->with('show_modal', true);
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
    public function update(EditProductoRequest $request, string $id)
    {
        try {
            $servicio = new ServicioProducto();
            $data = $request->validated();
            $data['sim'] = $request->has('sim') ? $request->sim : 'no';

            $servicio->actualizar($id, $data);
            return redirect()->route("productos.index")
                ->with('success_edit', 'Producto modificado correctamente.');
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
            $servicio = new ServicioProducto();
            $servicio->eliminar($id);
            return redirect()->route('productos.index')
                ->with('success-delete', 'Producto eliminado correctamente.');
        } catch (\Exception $ex) {
            return redirect()->back()
                ->withErrors(['general-error' => $ex->getMessage()])
                ->withInput()
                ->with('show_modal', true);
        }
    }
}
