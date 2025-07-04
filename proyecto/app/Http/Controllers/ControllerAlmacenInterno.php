<?php

namespace App\Http\Controllers;

use App\Models\AlmacenInterno;
use App\Services\ServicioAlmacenInterno;
use Illuminate\Http\Request;

class ControllerAlmacenInterno extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $servicio = new ServicioAlmacenInterno();
        $almaceninterno = $servicio->listar();

        return view("almaceninterno.index", compact("almaceninterno"));
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
    public function show($id)
    {
        $idsArray = explode(',', $id); 

        $items = AlmacenInterno::with(['compra', 'producto'])
            ->whereIn('id', $idsArray)
            ->get();
        $almaceninterno = $items;
        return view("almaceninterno.detalles", compact("almaceninterno"));
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
