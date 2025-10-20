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
                @if (session('success'))
                    <div class="alert alert-success msj mb-4 msj">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger msj mb-4 msj">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="container mt-5">
                    <div class="row justify-content-center">
                        <div class="col-md-6 col-lg-4">
                            <div class="card shadow-sm border-0">
                                <div class="card-body text-center">
                                    <h5 class="card-title mb-3 text-primary fw-bold">
                                        Aperturar Caja
                                    </h5>

                                    {{-- Mostrar estado actual --}}
                                    @if ($caja)
                                        <p class="mb-3">
                                            Estado actual:
                                            @if ($caja->estado === 'abierta')
                                                <span class="badge bg-success">Abierta</span>
                                            @else
                                                <span class="badge bg-secondary">Cerrada</span>
                                            @endif
                                        </p>
                                    @else
                                        <p class="mb-3 text-muted">No hay registros de caja aún.</p>
                                    @endif

                                    <form action="{{ route('caja.store') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="montoInicial" class="form-label fw-semibold">
                                                Monto Inicial de la Caja
                                            </label>
                                            <small class="d-block text-muted mb-2">
                                                Es el dinero con el que comienzas el día. Se usa como base para calcular el
                                                saldo final.
                                            </small>
                                            <input type="number" class="form-control text-center" id="montoInicial"
                                                name="monto_inicial" placeholder="Ingrese monto inicial" min="0"
                                                step="0.01" value="{{ $caja?->monto_final }}"
                                                {{ $caja?->estado == 'abierta' ? 'readonly' : '' }}>
                                        </div>


                                        @if ($caja?->estado == 'abierta')
                                            <button id="btnCerrar" class="btn btn-danger w-100">
                                                <i class="fas fa-cash-register me-2"></i> Cerrar Caja
                                            </button>
                                        @else
                                            <button id="btnAperturar" class="btn btn-success w-100">
                                                <i class="fas fa-cash-register me-2"></i> Aperturar Caja
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="container mt-2">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h4 id="btnhistor" style="cursor:pointer;"
                                class="card-title text-center mb-4 text-primary font-weight-bold">Historial
                                de
                                Cajas 👁️</h4>

                            <table style="display:none;" id="histor"
                                class="table table-hover table-striped align-middle">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Fecha Apertura</th>
                                        <th>Fecha Cierre</th>

                                        <th>Usuario</th>
                                        <th>Monto Inicial</th>
                                        <th>Monto Final</th>
                                        <th>Diferencia</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($cajas as $index => $c)
                                        <!-- Fila principal -->
                                        <tr class="fila-caja" data-target="#detalle{{ $index }}">
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ \Carbon\Carbon::parse($c->fecha_apertura)->format('d/m/Y H:i') }}
                                            </td>
                                            @if ($c->estado == 'abierta')
                                                <td><span class="text-danger font-weight-bold">Caja
                                                        actualmente abierta</span>
                                                </td>
                                            @else
                                                <td>{{ \Carbon\Carbon::parse($c->fecha_cierre)->format('d/m/Y H:i') }}
                                                </td>
                                            @endif

                                            <td>{{ $c->persona->name ?? '—' }}</td>
                                            <td>S/ {{ number_format($c->monto_inicial, 2) }}</td>
                                            <td>S/ {{ number_format($c->monto_final, 2) }}</td>
                                            @php
                                                $diferencia = $c->monto_final - $c->monto_inicial;
                                            @endphp
                                            <td class="{{ $diferencia >= 0 ? 'text-success' : 'text-danger' }}">
                                                S/ {{ number_format($diferencia, 2) }}
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $c->estado == 'abierta' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($c->estado) }}
                                                </span>
                                            </td>
                                        </tr>

                                        <!-- Fila de detalles -->
                                        <tr id="detalle{{ $index }}" class="collapse bg-light">
                                            <td colspan="6" class="p-4">
                                                <div>
                                                    <p><strong>Fecha Apertura:</strong>
                                                        {{ $c->fecha_apertura }}</p>
                                                    <p><strong>Fecha Cierre:</strong>
                                                        {{ $c->fecha_cierre ?? 'Aún abierta' }}</p>
                                                    <p><strong>Observación:</strong>
                                                        {{ $c->observacion ?? '—' }}</p>
                                                    @if ($c->estado == 'abierta')
                                                        <span class="text-danger font-weight-bold">Caja
                                                            actualmente abierta</span>
                                                    @else
                                                        <span class="text-muted">Caja cerrada</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No hay registros
                                                de cajas.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <hr>

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
                                    @if ($caja?->estado == 'abierta')
                                        <p>
                                            <strong>Saldo Diario:</strong>
                                            <strong
                                                style="color: {{ $caja?->monto_final + $totalSalidas - $totalGastos < 0 ? 'orangered' : 'green' }}">
                                                S/
                                                {{ number_format($caja?->monto_final + $totalSalidas - $totalGastos, 2) }}
                                            </strong>
                                            <input type="hidden"
                                                value="{{ number_format($caja?->monto_final + $totalSalidas - $totalGastos, 2) }}"
                                                id="monto_final">
                                        </p>
                                    @else
                                        <p>
                                            <strong>Saldo Diario:</strong>
                                            <strong
                                                style="color: {{ $totalSalidas - $totalGastos < 0 ? 'orangered' : 'green' }}">
                                                S/ {{ number_format($totalSalidas - $totalGastos, 2) }}
                                            </strong>

                                        </p>
                                    @endif

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('scripts')
        <script>
            if ($(".msj").length) {
                setTimeout(() => {
                    $(".msj").fadeOut();

                }, 4000);
            }
            document.addEventListener("DOMContentLoaded", () => {
                const button = document.getElementById("btnhistor");
                const cont = document.getElementById("histor");
                button.addEventListener("click", () => {
                    if (cont.style.display === "none" || !cont.style.display) {
                        cont.style.display = "block";
                    } else {
                        cont.style.display = "none";
                    }
                });

                const btnCerrar = document.getElementById("btnCerrar");

                btnCerrar.addEventListener("click", async () => {
                    if (!confirm("¿Seguro que deseas cerrar la caja?")) return;

                    const montoFinal = parseFloat(document.getElementById("monto_final").value) || 0;

                    try {
                        const response = await fetch("/caja/cerrar", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute("content")
                            },
                            body: JSON.stringify({
                                monto_final: montoFinal
                            })
                        });

                        const data = await response.json();

                        if (response.ok) {
                            alert(`✅ ${data.message}\nSaldo final: S/ ${data.monto_final}`);
                            location.reload();
                        } else {
                            alert(`❌ Error: ${data.message || "Intenta nuevamente."}`);
                        }
                    } catch (error) {
                        console.error(error);
                        alert("Error de conexión con el servidor.");
                    }
                });
            });
        </script>


        <script src="{{ asset('melody/data-table.js') }}"></script>
    @endsection
