<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Services\ServicioAbonoVenta;
use App\Services\ServicioPagos;
use App\Services\ServicioVenta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ControllerReportes extends Controller
{
    public function index()
    {
        $servicioAbonos = new ServicioAbonoVenta();
        $servicioPagos = new ServicioPagos();
        $servicioVentas = new ServicioVenta();
        $pagos = $servicioPagos->listarPagosPorFecha(now("America/Lima")->format("Y-m-d"));
        $abonos = $servicioAbonos->listarAbonosPorFecha(now("America/Lima")->format("Y-m-d"));

        $pagosMes = $servicioPagos->listarPagosDelMes();
        $pagosSemana = $servicioPagos->listarPagosDeLaSemana();

        $abonoMes = $servicioAbonos->listarAbonosDelMes();
        $abonoSemana = $servicioAbonos->listarAbonosDeLaSemana();

        $totalAbonosSemana = $abonoSemana->sum('monto');
        $totalPagosSemana = $pagosSemana->sum('monto_pagado');
        $utilidadSemanal = $totalAbonosSemana - $totalPagosSemana;

        $reporteSemanal = [
            'total_abonos' => $totalAbonosSemana,
            'total_pagos' => $totalPagosSemana,
            'utilidad' => $utilidadSemanal
        ];

        $totalAbonosMes = $abonoMes->sum('monto');
        $totalPagosMes = $pagosMes->sum('monto_pagado');
        $utilidadMensual = $totalAbonosMes - $totalPagosMes;

        $reporteMensual = [
            'total_abonos' => $totalAbonosMes,
            'total_pagos' => $totalPagosMes,
            'utilidad' => $utilidadMensual
        ];


        $clientestop = $servicioVentas->obtenerClientesTop();
        $ticketPromediomes = $servicioVentas->calcularTicketPromedioMes();
        $ticketPromedioAnual = $servicioVentas->calcularTicketPromedioAnual();
        $diasVentas = $this->ObtenerDiasDeLaSemanaConMasVentas();
        $totalIngresos = $abonos->sum("monto");
        $totalEgresos = $pagos->sum("monto_pagado");
        $utilidad = max(0, $totalIngresos - $totalEgresos);

        $cotizacionesPromedios = $this->calcularPorcentajesDeCotizaciones();
        $productosmasVendidos = $this->ObtenerCincoProductosMasvendidos();
        $productosPorAgotarse = $this->calcularProductosPorAgotarse();
        $gastosServicios = $this->calcularGastosServicios();
        return view("welcome", compact("diasVentas", "gastosServicios", "productosPorAgotarse", "productosmasVendidos", "cotizacionesPromedios", "reporteMensual", "reporteSemanal", "totalIngresos", "totalEgresos", "utilidad", "clientestop", 'ticketPromediomes', "ticketPromedioAnual"));
    }

    private function calcularGastosServicios()
    {
        $fechaInicio = Carbon::now()->subMonths(2)->startOfMonth();
        $fechaFin = Carbon::now()->endOfMonth();

        $gastos = DB::table('pagos')
            ->join('servicios', 'servicios.id', '=', 'pagos.servicio_id')
            ->select(
                DB::raw('YEAR(fecha_pago) as anio'),
                DB::raw('MONTH(fecha_pago) as mes'),
                'servicios.nombre as servicio',
                DB::raw('SUM(monto_pagado) as total')
            )
            ->whereBetween('fecha_pago', [$fechaInicio, $fechaFin])
            ->where('metodo_pago', '!=', 'Descuento-Saldo-Favor')
            ->groupBy('anio', 'mes', 'servicio')
            ->orderBy('anio', 'desc')
            ->orderBy('mes', 'desc')
            ->get();

        return $gastos;
    }

    private function  calcularProductosPorAgotarse()
    {
        return DB::table('almacen_internos')
            ->join('productos', 'almacen_internos.producto_id', '=', 'productos.id')
            ->select('almacen_internos.registrado', 'productos.marca', 'productos.modelo', 'productos.capacidad', 'producto_id', DB::raw('SUM(almacen_internos.cantidad) as stock_actual'))
            ->groupBy('almacen_internos.registrado', 'producto_id', 'productos.marca', 'productos.modelo', 'productos.capacidad')
            ->having('stock_actual', '<=', 5)
            ->get();
    }

    private function calcularPorcentajesDeCotizaciones()
    {
        $cotizaciones = Cotizacion::all();

        $totalPendientes = $cotizaciones->where('estado', 'Pendiente')->sum('total');
        $totalGeneradas = $cotizaciones->where('estado', 'Generado')->sum('total');
        $totalGeneral = $cotizaciones->sum('total');

        $porcentajePendientes = $totalGeneral > 0 ? ($totalPendientes / $totalGeneral) * 100 : 0;
        $porcentajeGeneradas = $totalGeneral > 0 ? ($totalGeneradas / $totalGeneral) * 100 : 0;

        return [
            'promedio_pendientes' => round($porcentajePendientes, 2),
            'promedio_generadas' => round($porcentajeGeneradas, 2),
        ];
    }

    private function ObtenerCincoProductosMasvendidos()
    {
        return DB::table('detalle_ventas as dv')
            ->join('productos as p', 'dv.producto_id', '=', 'p.id')
            ->join('ventas as v', 'dv.venta_id', '=', 'v.id')
            ->select('p.marca', 'p.modelo', 'p.capacidad', DB::raw('SUM(dv.cantidad) as total_vendidos'))
            ->whereMonth('v.fecha', Carbon::now()->month)
            ->whereYear('v.fecha', Carbon::now()->year)
            ->whereNotIn('v.estado', ['Esperando Aprobacion', 'anulado'])
            ->groupBy('dv.producto_id', 'p.marca', 'p.modelo', 'p.capacidad')
            ->orderByDesc('total_vendidos')
            ->limit(5)
            ->get();
    }

    public function ObtenerDiasDeLaSemanaConMasVentas()
    {
        $ventasPorDiaSemana = DB::table('ventas as v')
            ->select(
                DB::raw('DAYOFWEEK(v.fecha) as dia_orden'),
                DB::raw('DAYNAME(v.fecha) as dia_semana'),
                DB::raw('SUM(dv.cantidad) as total_vendidos')
            )
            ->join('detalle_ventas as dv', 'dv.venta_id', '=', 'v.id')
            ->where('v.fecha', '>=', Carbon::now()->subMonths(3)->startOfDay())
            ->whereNotIn('v.estado', ['Esperando Aprobacion', 'anulado'])
            ->groupBy(DB::raw('DAYOFWEEK(v.fecha), DAYNAME(v.fecha)'))
            ->orderByDesc('total_vendidos')
            ->get();

        $diaMasVentas = $ventasPorDiaSemana->first();
        $diaMenosVentas = $ventasPorDiaSemana->sortBy('total_vendidos')->first();

        return [
            'ventas_por_dia_semana' => $ventasPorDiaSemana,
            'dia_mas_ventas_semana' => $diaMasVentas,
            'dia_menos_ventas_semana' => $diaMenosVentas,
        ];
    }

     public function reportes(Request $request)
    {
        $servicioVentas = new ServicioVenta();
        $ventas = $servicioVentas->listarActivas();

        if ($request->has('semana')) {
            $desde = now()->subDays(7)->toDateString();
            $hasta = now()->toDateString();
            $ventas = $ventas->whereBetween('fecha', [$desde, $hasta]);
        }

        if ($request->filled('desde') && $request->filled('hasta')) {
            $ventas = $ventas->whereBetween('fecha', [$request->desde, $request->hasta]);
        }

        return view("reportes", compact("ventas"));
    }
}
