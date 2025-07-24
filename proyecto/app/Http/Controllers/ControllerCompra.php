<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditCompraRequest;
use App\Http\Requests\StoreCompraRequest;
use App\Models\Compra;
use App\Models\Persona;
use App\Models\Producto;
use App\Services\ServicioAlmacenInterno;
use App\Services\ServicioCompra;
use App\Services\ServicioCuentaBancaria;
use App\Services\ServicioCuentas;
use App\Services\ServicioDetalleCompra;
use App\Services\ServicioPagos;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ControllerCompra extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $compras = Compra::paginate(50);
        $servicio = new ServicioCompra();
        $servicioCuentas = new ServicioCuentaBancaria();
        $codigo = "CMP-" . $servicio->obtenerCodigo(Compra::max("id") + 1);
        $cuentas = $servicioCuentas->listarActivas();
        $proveedores = Persona::whereHas('tipo', function ($q) {
            $q->whereIn('tipo', ['ambos', 'proveedor']);
        })->with('tipo')->get();
        $productos = Producto::all();
        return view("compras.index", compact("compras", "codigo", "proveedores", "productos", "cuentas"));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompraRequest $request)
    {
        try {
            DB::beginTransaction();

            if (
                in_array($request->tipo_compra, ["mixto", "credito"]) &&
                in_array($request->estado, ["pagado", "anulado"])
            ) {
                DB::rollBack();
                return redirect()->back()
                    ->withErrors(['general' => "La compra es {$request->tipo_compra}, por lo tanto debe estar en estado pendiente de pago."])
                    ->withInput()
                    ->with('show_modal', true);
            }

            // Validar productos

            if (!isset($request["productos"]) || !is_array($request["productos"])) {
                DB::rollBack();
                return redirect()->back()
                    ->withErrors(['general' => "No se encontraron productos para registrar."])
                    ->withInput()
                    ->with('show_modal', true);
            }


          

            // Crear compra
            $servicio = new ServicioCompra();
            $compra = $servicio->crear($request->validated());
            $data = $request->all();
            $servicioDetalleCompra = new ServicioDetalleCompra();
            $servicioAlmacen = new ServicioAlmacenInterno();
            if (isset($data['productos']) && is_array($data['productos'])) {
                // Recorremos cada producto y añadimos compra_id
                foreach ($data['productos'] as &$producto) {
                    $producto['compra_id'] = $compra->id;
                    $servicioDetalleCompra->crear($producto);
                    $servicioAlmacen->crear($producto);
                }
                unset($producto);
            }
            $controladorPagos = new ControllerPagos();
            $controladorPagos->GuardarPagoCompra($request->all(), $compra->id, $compra->persona_id);

            DB::commit();
            return redirect()->route('compras.index')
                ->with('success', 'Compra registrada correctamente.');
        } catch (\Exception $ex) {
            DB::rollBack();
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
        $servicioCompra = new ServicioCompra();
        $servicioDetalleCompra = new ServicioDetalleCompra();

        $compra = $servicioCompra->obtenerPorId($id);
        $detallescompra = $servicioDetalleCompra->obtenerPorId($id);

        return view("compras.detalles", compact("compra", "detallescompra"));
    }


    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(EditCompraRequest $request, string $id)
    {
        try {
            DB::beginTransaction();

            $tipo_compra = strtolower(trim($request->tipo_compra));
            $estado = strtolower(trim($request->estado));

            if (
                in_array($tipo_compra, ["mixto", "credito"]) &&
                in_array($estado, ["pagado", "anulado"])
            ) {
                throw new Exception("La compra es  {$tipo_compra}, por lo tanto debe estar en estado Pendiente de pago.");
            }
            if (
                in_array($tipo_compra, ["contado"]) &&
                in_array($estado, ["pendiente"])
            ) {
                throw new Exception("La compra es al Contado, por lo tanto su estado de compra debe de ser Pagado.");
            }

            $servicio = new ServicioCompra();
            $compra_id = $servicio->obtenerPorId($id);
            if ($compra_id->estado == "anulado") {
                throw new Exception("La compra ya ha Sido Anulada. No se puede Modificar.");
            }
            $servicio->actualizar($id, $request->validated());
            if ($estado == "anulado") {
                $servicioalmacen = new ServicioAlmacenInterno();
                $servicioalmacen->eliminar($id);
            }
            DB::commit();
            return redirect()->route('compras.index')
                ->with('success_edit', 'Compra modificada correctamente.');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['general_edit' => $ex->getMessage()])
                ->withInput()->with("show_modal_edit", true);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $servicioCompra = new ServicioCompra();
            $servicioalmacen = new ServicioAlmacenInterno();
            $servicioalmacen->eliminar($id);
            $servicioCompra->actualizar($id, ["estado" => "anulado"]);
            $servicioPagos = new ServicioPagos();
            $pagos = $servicioPagos->obtenerPagosCompra($id);
            foreach ($pagos as $pago) {
                $pago->delete();
            }
            return redirect()->route('compras.index')
                ->with('success-delete', 'Compra Anulada correctamente.');
        } catch (\Exception $ex) {
            return redirect()->back()
                ->withErrors(['general-error' => $ex->getMessage()]);
        }
    }

    public function ListarSaldoFavorCliente(string $id)
    {
        $servicioCompra = new ServicioCompra();
        $comprasConDeudaDePago = $servicioCompra->listarCuentasPendientesProveedor($id);
        $saldoAFavor = $comprasConDeudaDePago->sum('total');
        return response()->json(["mensaje" => $saldoAFavor]);
    }
}
