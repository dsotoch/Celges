<?php

namespace App\Http\Controllers;

use App\Models\AlmacenInterno;
use App\Models\Cotizacion;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ControllerVentas extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $max = Venta::max("id");
        $codigo = "VEN" . str_pad($max + 1, 4, "0", STR_PAD_LEFT);

        $ventas_del_dia = Venta::where('fecha', Carbon::now("America/Lima")->format("Y-m-d"));
        $ventas = Venta::paginate(50);
        $cotizaciones = Cotizacion::all();
        return view("ventas.index", compact("ventas_del_dia", "ventas", "cotizaciones","codigo"));
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
    public function store(Request $request) {}


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
