@extends('partials.layout')
@section('estilos')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
@endsection
@section('pagina')
    <div class="content-wrapper ">
        @component('componentes.com_titulo', [
            'titulo' => 'Gestión de Caja',
            'paginaprincipal' => 'Caja',
            'paginaactual' => 'Caja Diaria',
        ])
        @endcomponent

        <div class="container mt-4">
            <div class="card shadow">
                <div class="card-header bg-orange text-white">
                    <h5 class="mb-0">📦 Control Diario de Caja</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="d-flex align-items-center ml-4 mb-3">
                            <i class="fas fa-money-bill-wave text-success mr-2" style="font-size: 1.5rem;"></i>
                            <div>
                                <h5 class="mb-0">Solo Efectivo</h5>
                                <small class="text-muted">Pagos realizados exclusivamente en efectivo</small>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <table class="table table-bordered table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Descripción</th>
                                        <th>Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- ABONOS EN EFECTIVO (Ingresos) --}}
                                    @foreach ($abonosEfectivo as $abono)
                                        <tr>
                                            <td><span class="badge badge-success">Ingreso</span></td>
                                            <td>{{ $abono->venta->cliente->nombres ?? 'Abono de cliente' }}</td>
                                            <td class="text-success">+ S/ {{ number_format($abono->monto, 2) }}</td>
                                        </tr>
                                    @endforeach

                                    {{-- PAGOS EN EFECTIVO (Egresos) --}}
                                    @foreach ($pagosEfectivo as $pago)
                                        <tr>
                                            <td><span class="badge badge-danger">Gasto</span></td>
                                            <td>{{ $pago->servicio->nombre }} **
                                                {{ $pago->persona->nombres ?? $pago->nota }}
                                            </td>
                                            <td class="text-danger">- S/ {{ number_format($pago->monto_pagado ?? 0, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                @php
                                    $totalIngresos = array_sum(array_map(fn($a) => $a->monto, $abonosEfectivo));
                                    $totalEgresos = array_sum(
                                        array_map(fn($p) => $p->monto_pagado ?? 0, $pagosEfectivo),
                                    );

                                    $totalEnCaja = $totalIngresos - $totalEgresos;

                                @endphp


                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-right font-weight-bold ">Total Ingresos
                                            :</td>
                                        <td class="text-success font-weight-bold">
                                            + S/ {{ number_format($totalIngresos, 2) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-right font-weight-bold">Total Egresos
                                            :</td>
                                        <td class="text-danger font-weight-bold">
                                            - S/ {{ number_format($totalEgresos, 2) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-right font-weight-bold">Total En Caja
                                            (Efectivo):</td>
                                        <td class=" font-weight-bold">
                                            S/ {{ number_format($totalEnCaja, 2) }}
                                        </td>
                                    </tr>
                                </tfoot>

                            </table>
                        </div>


                        <hr>
                        <div class="d-flex align-items-center mb-4 col-12 mt-4">
                            <i class="fas fa-exchange-alt text-warning mr-2" style="font-size: 1.5rem;"></i>

                            <div>
                                <div class="d-flex align-items-baseline">
                                    <h5 class="mb-0 mr-2">Pago Mixto</h5>
                                    <small class="text-muted">Parte en efectivo, parte con tarjeta u otros medios</small>
                                </div>
                            </div>
                        </div>

                        <!-- Entradas -->
                        <div class="col-md-6 mb-3">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <strong>Entradas</strong>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        @php
                                            $iconoMetodo = [
                                                'Efectivo' => 'fas fa-money-bill-wave',
                                                'Transferencia' => 'fas fa-exchange-alt',
                                                'Yape' => 'fab fa-y-combinator',
                                                'Plin' => 'fas fa-mobile-alt',
                                                'Tarjeta' => 'fas fa-credit-card',
                                                'Descuento-Saldo-Favor' => 'fas fa-percentage',
                                                'Otros' => 'fas fa-wallet',
                                            ];
                                        @endphp

                                        <ul class="list-group mt-3">
                                            @php
                                                $totalGeneral = 0;
                                                $totalSalidas = 0;
                                            @endphp

                                            @foreach ($abonosPorMetodoCuenta as $metodo => $cuentas)
                                                @foreach ($cuentas as $cuenta => $monto)
                                                    @php
                                                        $icono = $iconoMetodo[$metodo] ?? 'fas fa-wallet';
                                                        $totalGeneral += $monto;
                                                        $totalSalidas += $monto;
                                                    @endphp
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <i class="{{ $icono }}"></i>
                                                            <strong class="ml-1">{{ $metodo }}</strong>
                                                            <small class="text-muted ml-2">({{ $cuenta }})</small>
                                                        </div>
                                                        <span class="badge badge-success badge-pill">
                                                            S/ {{ number_format($monto, 2) }}
                                                        </span>
                                                    </li>
                                                @endforeach
                                            @endforeach

                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center bg-light font-weight-bold">
                                                <span><i class="fas fa-calculator"></i> Total General</span>
                                                <span class="text-primary">S/ {{ number_format($totalGeneral, 2) }}</span>
                                            </li>
                                        </ul>

                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Salidas / Gastos -->
                        <div class="col-md-6 mb-3">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white">
                                    <strong>Salidas / Gastos</strong>
                                </div>
                                <div class="card-body">

                                    @php
                                        $totalGeneral = 0;
                                        $totalGastos = 0;
                                    @endphp

                                    <ul class="list-group list-group-flush">
                                        <ul class="list-group mt-3">
                                            @foreach ($pagosAgrupados as $metodo => $categorias)
                                                {{-- Encabezado del método de pago --}}
                                                <li class="list-group-item border">
                                                    <i class="fas fa-wallet"></i> {{ $metodo }}
                                                </li>

                                                {{-- Listado de categorías con sus montos --}}
                                                @foreach ($categorias as $categoria => $monto)
                                                    @php
                                                        $totalGeneral += $monto;
                                                        $totalGastos += $monto;
                                                    @endphp
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <span>
                                                            <i class="fas fa-money-bill-wave text-secondary mr-1"></i>
                                                            {{ $categoria }}
                                                        </span>
                                                        <span class="text-danger font-weight-bold">S/
                                                            {{ number_format($monto, 2) }}</span>
                                                    </li>
                                                @endforeach
                                            @endforeach
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center bg-light font-weight-bold">
                                                <span><i class="fas fa-calculator"></i> Total General</span>
                                                <span class="text-primary">S/ {{ number_format($totalGeneral, 2) }}</span>
                                            </li>
                                        </ul>

                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Resumen -->
                        <div class="col-12 mt-3">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <strong>Resumen Final</strong>
                                </div>
                                <div class="card-body">
                                    <p><strong>Total Entradas:</strong> <strong class="text-primary">S/
                                            {{ number_format($totalSalidas, 2) }}</strong></p>
                                    <p><strong>Total Salidas:</strong> <strong class="text-primary">S/
                                            {{ number_format($totalGastos, 2) }}</strong></p>
                                    <p><strong>Saldo Diario:</strong> <strong style="color: orangered">S/
                                            {{ number_format($totalSalidas - $totalGastos, 2) }}</strong></p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('scripts')
        <script src="{{ asset('melody/data-table.js') }}"></script>
    @endsection
