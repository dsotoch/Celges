@extends('partials.layout')
@section('pagina')
    <div class="content-wrapper">
        @component('componentes.com_titulo', [
            'titulo' => 'Dashboard',
            'paginaprincipal' => 'Dashboard',
            'paginaactual' => 'Dashboard',
        ])
        @endcomponent
        @hasrole('admin')
            <div class="row grid-margin">
                <div class="col-12">
                    <div class="card bg-gradient-orange">
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-4 col-12 mb-4">
                                    <div class="statistics-item">
                                        <i class="fas fa-shopping-cart"></i>
                                        <p class="mb-1"><strong>Ingresos del día</strong></p>
                                        <h4>S/ {{ number_format($totalIngresos, 2) }}</h4>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12 mb-4">
                                    <div class="statistics-item">
                                        <i class="fas fa-money-bill-wave"></i>
                                        <p class="mb-1"><strong>Gastos del día</strong></p>
                                        <h4>S/ {{ number_format($totalEgresos, 2) }}</h4>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12 mb-4">
                                    <div class="statistics-item">
                                        <i class="fas fa-chart-line"></i>
                                        <p class="mb-1"><strong>Utilidad neta</strong></p>
                                        <h4>S/ {{ number_format($utilidad, 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>




                </div>
            </div>

            <div class="row">
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card border-left-info shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-chart-bar fa-3x text-info mb-3"></i>
                            <h4 class="card-title font-weight-bold text-info">Utilidad Semanal</h4>
                            <h3 class="text-dark font-weight-bold mb-3">S/
                                {{ number_format($reporteSemanal['utilidad'], 2) }}</h3>
                            <p class="mb-1">
                                <i class="fas fa-hand-holding-usd text-muted"></i>
                                <strong>Abonos:</strong>
                                <span class="text-success">S/
                                    {{ number_format($reporteSemanal['total_abonos'], 2) }}</span>
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-money-bill-wave text-muted"></i>
                                <strong>Pagos:</strong>
                                <span class="text-danger">S/
                                    {{ number_format($reporteSemanal['total_pagos'], 2) }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card border-left-primary shadow-sm h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-alt fa-3x text-primary mb-3"></i>
                            <h4 class="card-title font-weight-bold text-primary">Utilidad Mensual
                                {{ \Carbon\Carbon::now()->translatedFormat('F') }}</h4>
                            <h3 class="text-dark font-weight-bold mb-3">S/
                                {{ number_format($reporteMensual['utilidad'], 2) }}</h3>
                            <p class="mb-1">
                                <i class="fas fa-hand-holding-usd text-muted"></i>
                                <strong>Abonos:</strong>
                                <span class="text-success">S/
                                    {{ number_format($reporteMensual['total_abonos'], 2) }}</span>
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-money-bill-wave text-muted"></i>
                                <strong>Pagos:</strong>
                                <span class="text-danger">S/
                                    {{ number_format($reporteMensual['total_pagos'], 2) }}</span>
                            </p>
                        </div>
                    </div>
                </div>


            </div>
            <div class="row">

                <div class="col-md-4 mb-4">
                    <div class="card border-left-primary shadow h-100">
                        <div class="card-body d-flex align-items-center justify-content-center flex-column text-center">
                            <i class="fas fa-receipt fa-3x text-primary mb-3"></i>
                            <h5 class="font-weight-bold text-primary">Ticket Promedio {{ now()->year }}</h5>
                            <h3 class="text-dark font-weight-bold">S/
                                {{ number_format($ticketPromedioAnual['ticket_promedio'], 2) }}</h3>
                            <hr class="w-75">
                            <p class="mb-1">
                                <i class="fas fa-cash-register text-muted"></i>
                                <strong>Total vendido:</strong><br>
                                <span class="text-primary">S/
                                    {{ number_format($ticketPromedioAnual['total_vendido'], 2) }}</span>
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-shopping-cart text-muted"></i>
                                <strong>Nº de ventas:</strong><br>
                                <span class="text-primary">{{ $ticketPromedioAnual['numero_ventas'] }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card border-left-success shadow h-100">
                        <div class="card-body d-flex align-items-center justify-content-center flex-column text-center">
                            <i class="fas fa-calendar-alt fa-3x text-success mb-3"></i>
                            <h5 class="font-weight-bold text-success">Ticket Promedio -
                                {{ \Carbon\Carbon::now()->translatedFormat('F') }}</h5>
                            <h3 class="text-dark font-weight-bold">S/
                                {{ number_format($ticketPromediomes['ticket_promedio'], 2) }}</h3>
                            <hr class="w-75">
                            <p class="mb-1">
                                <i class="fas fa-cash-register text-muted"></i>
                                <strong>Total vendido:</strong><br>
                                <span class="text-primary">S/
                                    {{ number_format($ticketPromediomes['total_vendido'], 2) }}</span>
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-shopping-cart text-muted"></i>
                                <strong>Nº de ventas:</strong><br>
                                <span class="text-primary">{{ $ticketPromediomes['numero_ventas'] }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">
                                <i class="far fa-futbol"></i>
                                Clientes Top Mes {{ \Carbon\Carbon::now()->translatedFormat('F') }}

                            </h4>
                            <ul class="solid-bullet-list">
                                @foreach ($clientestop as $cliente)
                                    <li>
                                        <h5>{{ $cliente['cliente']['nombres'] }}
                                            <span
                                                class="float-right text-muted font-weight-normal h5">{{ now('America/lima')->format('H:m') }}
                                            </span>
                                        </h5>
                                        <p class="text-muted">S/ {{ $cliente['total_comprado'] }}</p>
                                        <div class="d-flex">
                                            <div class="img-sm profile-image-text bg-warning rounded-circle image-layer-item">
                                            </div>
                                            <p>📞{{ $cliente['cliente']['telefono'] }}</p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        @endhasrole
        @hasanyrole('admin|vendedor')
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">
                                <i class="fas fa-box-open text-danger"></i>
                                Productos por Agotarse
                            </h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Marca</th>
                                            <th>Modelo</th>
                                            <th>Capacidad</th>
                                            <th>Registrado</th>
                                            <th>Stock Actual</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($productosPorAgotarse as $producto)
                                            <tr>
                                                <td class="font-weight-bold">{{ $producto->marca }}</td>
                                                <td>{{ $producto->modelo }}</td>
                                                <td>{{ $producto->capacidad }}</td>
                                                <td class="text-muted">
                                                    {{ $producto->registrado == 1 ? '✅' : '🔴' }}</td>
                                                <td>{{ $producto->stock_actual }}</td>
                                                <td>
                                                    @if ($producto->stock_actual <= 2)
                                                        <label class="badge badge-danger badge-pill">Crítico</label>
                                                    @elseif ($producto->stock_actual <= 5)
                                                        <label class="badge badge-warning badge-pill">Bajo</label>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        @endhasanyrole
        @hasrole('admin')
            <div class="row">
                <div class="col-md-8 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">
                                <i class=" fas fa-tag"></i>
                                Productos mas vendidos
                            </h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        @foreach ($productosmasVendidos as $producto)
                                            <tr>

                                                <td class="py-1">
                                                    <div
                                                        class="img-sm rounded-circle bg-primary profile-image-text text-white text-center d-flex align-items-center justify-content-center">
                                                        <span>{{ strtoupper(substr($producto->marca, 0, 1)) }}</span>
                                                    </div>
                                                </td>
                                                <td class="font-weight-bold">
                                                    {{ $producto->marca }} {{ $producto->modelo }} {{ $producto->capacidad }}
                                                </td>
                                                <td>
                                                    <label class="badge badge-pill badge-info">
                                                        Vendidos: {{ $producto->total_vendidos }}
                                                    </label>
                                                </td>
                                                <td>
                                                    <i class="fas fa-box text-muted mr-1"></i>
                                                    Producto más vendido
                                                </td>
                                                <td>
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 grid-margin stretch-card">

                    <div class="card shadow-sm border-left-info h-100 py-3 px-3">
                        <div class="card-body text-center">
                            <i class="fas fa-file-invoice-dollar fa-3x text-info mb-3"></i>
                            <h5 class="font-weight-bold text-info">Promedio de Cotizaciones</h5>
                            <hr>
                            <p class="mb-4">
                                <i class="fas fa-hourglass-half text-muted fa-lg mb-2 d-block"></i>
                                <strong class="d-block">Pendientes:</strong>
                                <span class="text-dark h4">
                                    {{ number_format($cotizacionesPromedios['promedio_pendientes'], 2) }} %
                                </span>
                            </p>

                            <p class="mb-4">
                                <i class="fas fa-check-circle text-muted fa-lg mb-2 d-block"></i>
                                <strong class="d-block">Generadas:</strong>
                                <span class="text-dark h4">
                                    {{ number_format($cotizacionesPromedios['promedio_generadas'], 2) }} %
                                </span>
                            </p>

                        </div>
                        <div class="card border-left-info shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3">
                                    <i class="fas fa-info-circle text-info mr-2"></i>
                                    Leyenda de Cotizaciones
                                </h5>

                                <p class="mb-3">
                                    <i class="fas fa-hourglass-half text-warning mr-2"></i>
                                    <strong>Pendientes:</strong> Aún no se han convertido en ventas.
                                </p>

                                <p class="mb-0">
                                    <i class="fas fa-check-circle text-success mr-2"></i>
                                    <strong>Generadas:</strong> Cotizaciones que ya han sido aprobadas y convertidas en ventas.
                                </p>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <div class="row">


                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">
                                <i class="fas fa-money-bill-wave"></i>
                                Gastos por Servicios (Últimos 3 meses)
                            </h4>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="bg-orange text-white">
                                                <i class="fas fa-calendar-alt"></i> Mes/Año
                                            </th>
                                            <th class="bg-orange text-white">
                                                <i class="fas fa-concierge-bell"></i> Servicio
                                            </th>
                                            <th class="bg-orange text-white">
                                                <i class="fas fa-money-bill-wave"></i> Total Pagado
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($gastosServicios as $gasto)
                                            <tr>
                                                <td class="font-weight-bold">
                                                    {{ \Carbon\Carbon::create($gasto->anio, $gasto->mes)->format('F Y') }}
                                                </td>
                                                <td>{{ $gasto->servicio }}</td>
                                                <td>S/ {{ number_format($gasto->total, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @endhasrole
    </div>
@endsection
