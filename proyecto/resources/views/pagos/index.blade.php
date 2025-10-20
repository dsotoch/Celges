@extends('partials.layout')
@section('estilos')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
@endsection
@section('pagina')
    <div class="content-wrapper ">
        @component('componentes.com_titulo', [
            'titulo' => 'Gestión de Pagos',
            'paginaprincipal' => 'Pagos Y Servicios',
            'paginaactual' => 'Pagos',
        ])
        @endcomponent

        <div class="container mt-4">
            <div class="card shadow-lg">
                <div class="card-header bg-orange text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-friends"></i> Todas las Cuentas y Servicios por Pagar
                    </h5>

                </div>
                @if (session('success'))
                    <div class="alert alert-success mb-2 mt-2 msj">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger mb-2 mt-2 msj">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="container mt-4">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="proveedores-tab" data-toggle="tab" href="#proveedores"
                                role="tab">Proveedores</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="gastos-tab" data-toggle="tab" href="#gastos" role="tab">Gastos
                                Fijos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="gastos-tab-otros" data-toggle="tab" href="#otros" role="tab">Otros
                                Gastos</a>
                        </li>
                    </ul>

                    <div class="tab-pane fade show active" id="proveedores" role="tabpanel">
                        <div class="accordion" id="proveedoresAccordion">
                            @php
                                $proveedores = $compras->groupBy('persona.id');
                            @endphp

                            @forelse ($proveedores as $idProveedor => $comprasProveedor)
                                @php
                                    $proveedor = $comprasProveedor->first()->persona;
                                    $totalProveedor = $comprasProveedor->sum('total');
                                    $totalPagado = $comprasProveedor->sum(
                                        fn($c) => $pagos[$c->id]->sum('monto_pagado'),
                                    );
                                    $saldo = $totalProveedor - $totalPagado;
                                @endphp

                                <div class="card mb-2">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link text-left toggle-collapse" type="button"
                                                data-target="collapseProveedor{{ $idProveedor }}">
                                                {{ $proveedor->nombres }}
                                            </button>
                                        </h5>
                                        <div>
                                            <span class="badge badge-info">Total: S/
                                                {{ number_format($totalProveedor, 2) }}</span>
                                            <span class="badge badge-success">Pagado: S/
                                                {{ number_format($totalPagado, 2) }}</span>
                                            <span class="badge badge-warning">Saldo: S/
                                                {{ number_format($saldo, 2) }}</span>
                                        </div>
                                    </div>

                                    <div id="collapseProveedor{{ $idProveedor }}" class="collapse-panel"
                                        style="display: none;">
                                        <div class="card-body">
                                            <table class="table table-sm table-bordered mb-0">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Compra</th>
                                                        <th>Producto</th>
                                                        <th>Total</th>
                                                        <th>Pagado</th>
                                                        <th>Saldo</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($comprasProveedor as $compra)
                                                        <tr>
                                                            <td>
                                                                {{ $compra->numero }}
                                                            </td>
                                                            <td>
                                                                @if ($compra->detalle && $compra->detalle->count())
                                                                    <ul class="mb-0 ps-3">
                                                                        @foreach ($compra->detalle as $detalle)
                                                                            <li>{{ $detalle->producto->marca }}
                                                                                {{ $detalle->producto->modelo }}
                                                                                {{ $detalle->producto->capacidad }}
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                @else
                                                                    —
                                                                @endif
                                                            </td>
                                                            <td>S/ {{ number_format($compra->total, 2) }}</td>
                                                            <td>S/
                                                                {{ number_format($pagos[$compra->id]->sum('monto_pagado'), 2) }}
                                                            </td>
                                                            <td>S/
                                                                {{ number_format($compra->total - $pagos[$compra->id]->sum('monto_pagado'), 2) }}
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-sm btn-success btnregistrarpago"
                                                                    title="Pagar" data-toggle="modal"
                                                                    data-target="#registroPagoModal"
                                                                    data-id="{{ $compra->id }}"
                                                                    data-cliente="{{ $compra->persona->id }}"
                                                                    data-total="{{ $compra->total - $pagos[$compra->id]->sum('monto_pagado') }}">
                                                                    <i class="fas fa-money-bill-wave"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-muted">No hay deudas pendientes.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endsection
    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const buttons = document.querySelectorAll('.toggle-collapse');

                buttons.forEach(btn => {
                    btn.addEventListener('click', () => {
                        const targetId = btn.getAttribute('data-target');
                        const target = document.getElementById(targetId);

                        if (!target) return;

                        document.querySelectorAll('.collapse-panel').forEach(panel => {
                            if (panel.id !== targetId) {
                                panel.style.display = 'none';
                            }
                        });

                        // Alternar visibilidad del panel actual
                        target.style.display = target.style.display === 'none' ? 'block' : 'none';
                    });
                });
            });
        </script>
        <script src="{{ asset('melody/data-table.js') }}"></script>
        @if (session('success_servicio'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var tabTrigger = new bootstrap.Tab(document.querySelector('#gastos-tab'));
                    tabTrigger.show();
                });
            </script>
        @endif



        <script>
            let totaldeuda = "";


            function EliminarPago(event) {
                event.preventDefault();
                if (confirm("¿Estas Seguro de Eliminar el Registro?")) {
                    event.target.closest("form").submit();
                }
            }

            if ($(".msj").length) {
                setTimeout(() => {
                    $(".msj").fadeOut();

                }, 3000);
            }
            async function GuardarPagos(event) {
                event.preventDefault();
                let confirmacion = confirm(
                    "⚠️ ¿Estás seguro de guardar los Pagos. Revisa bien los datos antes de confirmar.?");
                let total = 0.00;
                const tabGastos = document.getElementById("gastos");
                const tabGastosOtros = document.getElementById("otros");
                let pestañaActiva = null;

                if (tabGastos.classList.contains("show") && tabGastos.classList.contains("active")) {
                    pestañaActiva = "gastos";
                    total = totaldeudaServicio;
                } else if (tabGastosOtros.classList.contains("show") && tabGastosOtros.classList.contains("active")) {
                    pestañaActiva = "otros";
                    total = totaldeudaServicio; // puedes cambiar esta variable según tu lógica
                } else {
                    total = totaldeuda;
                }
                if (confirmacion) {
                    let response = await calcularPagos(total, pestañaActiva);
                    if (response == "ok") {
                        event.target.closest("form").submit();

                    }
                }
            }

            async function calcularPagos(total, tipo) {
                let divpagos = $("#cuerpopagos");
                let totalmontos = 0.00;
                let montos = divpagos.find("[name='monto[]']");

                montos.each(function() {
                    const valor = parseFloat($(this).val()) || 0;
                    totalmontos += valor;
                });

                let tabGastos = document.getElementById("gastos");
                if (tipo == "otros") {
                    let tabGastos = document.getElementById("otros");

                }

                if (totalmontos === 0.00) {
                    alert("💰 Ingresa un monto válido. No hay detalles de pago.");
                    return "error";
                }
                if (totalmontos < total) {
                    const tabGastos = document.getElementById("gastos");

                    if (tabGastos.classList.contains("show") && tabGastos.classList.contains("active")) {
                        alert(
                            "💰 La suma de todos los pagos es menor al total del pago del Servicio.");
                    } else {
                        alert(
                            "💰 La suma de todos los pagos es menor al total de la Compra.");
                    }
                    return "error";

                }
                if (totalmontos > total) {
                    if (tabGastos.classList.contains("show") && tabGastos.classList.contains("active")) {
                        alert(
                            "💰 La suma de todos los pagos es mayor al total del pago del Servicio.");
                    } else {
                        alert(
                            "💰 La suma de todos los pagos es mayor al total de la Compra."
                        );
                    }
                    return "error";
                }

                return "ok";
            }


            let totaldeudaServicio = 0.00;
            $(".btnregistrarpagoServicio").on("click", function() {
                let total = this.dataset.total;
                let id = this.dataset.id;
                totaldeudaServicio = total;
                $("#n_ventapago").html("S/" + total);
                $("#compra_id").val(id);

            });

            $(".btnregistrarpago").on("click", function() {
                let total = this.dataset.total;
                let id = this.dataset.id;
                let cliente = this.dataset.cliente;
                totaldeuda = total;
                $("#n_ventapago").html("S/" + total);
                $("#cliente_id").val(cliente);
                $("#compra_id").val(id);

            });
            $("#metodo_pago").on("change", function() {
                const valor = $(this).val();
                if (valor === "Transferencia") {
                    $(".banco").removeClass("oculto");
                    $(".operacion").removeClass("oculto");
                    $(".monto").removeClass("oculto");
                    $(".detallespagos").removeClass("oculto");

                } else {
                    if (valor === "Efectivo") {
                        $(".monto").removeClass("oculto");
                        $(".operacion").addClass("oculto");
                        $(".banco").addClass("oculto");
                        $(".detallespagos").removeClass("oculto");
                    } else {
                        $(".operacion").addClass("oculto");
                        $(".monto").addClass("oculto");
                        $(".banco").addClass("oculto");
                        $(".detallespagos").addClass("oculto");

                    }
                }
            });

            $(document).on('click', '#btnagregarPago', function() {
                const metodo_pago = $("#metodo_pago")?.val();
                const banco = $("#banco")?.val();
                let operacion = $("#numero_operacion")?.val();
                const monto = $("#monto")?.val();


                if (metodo_pago == "Efectivo" && (monto == "" || monto <= 0)) {
                    alert("💢 Ingresa un monto valido");
                    return;
                }
                if (metodo_pago == "Transferencia" && (banco == "")) {
                    alert("💢 Seleccione la cuenta de banco.");
                    return;
                }
                if (metodo_pago == "Transferencia" && banco != "" && operacion == "") {
                    alert("💢 Ingrese el Numero de Operacion.");
                    return;
                }
                if (metodo_pago == "Transferencia" && banco != "" && operacion != "" && (monto == "" || monto <= 0)) {
                    alert("💢 Ingresa un monto valido");
                    return;
                }

                let bancoNombre = "---";
                if (metodo_pago == "Transferencia") {
                    bancoNombre = $("#banco option:selected").text();
                } else {
                    operacion = "0";
                }
                const fechaActual = new Date().toLocaleString('en-CA', {
                    timeZone: 'America/Lima',
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit'
                });

                $("#cuerpopagos").append(`
    <div class="card mb-3">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-6">
                    <strong>Método de pago:</strong>
                    <p class="mb-1">${metodo_pago}</p>
                </div>
                <div class="col-md-6">
                    <strong>Operación:</strong>
                    <p class="mb-1">${operacion}</p>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-12">
                    <strong>Banco:</strong>
                    <p class="mb-1">${bancoNombre}</p>
                </div>
                
            </div>
            <div class="row mb-2">
                <div class="col-md-6">
                    <strong>Monto:</strong>
                    <p class="mb-1">${monto}</p>
                </div>
                <div class="col-md-6 d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Fecha:</strong>
                        <p class="mb-1">${fechaActual}</p>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger btn-eliminar-pago">Eliminar</button>
                </div>
            </div>

            <!-- Campos ocultos para el envío del formulario -->
            <input type="hidden" name="numero[]" value="${operacion}">
            <input type="hidden" name="tipo[]" value="${metodo_pago}">
            <input type="hidden" name="monto[]" value="${monto}">
            <input type="hidden" name="fecha[]" value="${fechaActual}">
            <input type="hidden" name="cuenta_id[]" value="${banco}">
        </div>
    </div>
`);



            });


            $(document).on("click", ".btn-eliminar-pago", function() {
                $(this).closest(".card").remove();
            });

            function detallesDeuda(cuentaId) {
                fetch(`cuentas/${cuentaId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("Error al obtener los detalles.");
                        }
                        return response.json();
                    })
                    .then(data => {
                        const detalles = data;
                        const container = document.getElementById('cards-detalles-cuentas');
                        container.innerHTML = ''; // Limpiar cards anteriores

                        if (detalles.length === 0) {
                            container.innerHTML = '<div class="col-12 text-center">Sin cuentas registradas.</div>';
                        } else {
                            detalles.forEach(cuenta => {
                                const abonos = cuenta.venta?.abonos || [];

                                let abonosHTML = '';
                                if (abonos.length > 0) {
                                    abonosHTML += `
            <div class="mt-3">
                <h6><i class="fas fa-list-alt text-secondary"></i> Detalles de pagos:</h6>
                <ul class="list-group">`;

                                    abonos.forEach(abono => {
                                        abonosHTML += `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-hand-holding-usd text-success"></i> 
                        <strong>Monto:</strong> S/ ${parseFloat(abono.monto).toFixed(2)} <br>
                        <i class="fas fa-calendar text-info"></i> 
                        <strong>Fecha:</strong> ${abono.fecha} <br>
                        <i class="fas fa-credit-card text-dark"></i> 
                        <strong>Método:</strong> ${abono.metodo_pago} <br>
                        <i class="fas fa-receipt text-secondary"></i> 
                        <strong>N° Operacion:</strong> ${abono.operacion?.numero ?? 'N/A'}
                    </div>
                    <span class="badge badge-pill badge-success">S/ ${parseFloat(abono.monto).toFixed(2)}</span>
                </li>`;
                                    });

                                    abonosHTML += `</ul></div>`;
                                } else {
                                    abonosHTML =
                                        `<div class="mt-3 text-muted"><em>No se han registrado abonos.</em></div>`;
                                }

                                const card = `
        <div class="col-md-12 mb-3">
            <div class="card border-primary shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">#${cuenta.venta.codigo}</h5>
                    <p class="card-text">
                        <i class="fas fa-money-bill-wave text-success"></i> 
                        <strong>Total:</strong> <span class="text-dark">S/ ${parseFloat(cuenta.total).toFixed(2)}</span><br><br>

                        <i class="fas fa-coins text-danger"></i> 
                        <strong>Deuda:</strong> <span class="text-danger">S/ ${parseFloat(cuenta.deuda).toFixed(2)}</span><br><br>

                        <i class="fas fa-calendar text-info"></i> 
                        <strong>Fecha:</strong> <span class="text-muted">${cuenta.venta?.fecha ?? 'N/A'}</span><br><br>

                        <i class="fas fa-tags text-primary"></i> 
                        <strong>Tipo:</strong> <span class="badge badge-pill badge-primary">${cuenta.venta?.tipo_venta ?? 'N/A'}</span>
                    </p>

                    ${abonosHTML}

                    <a href="/ventas/${cuenta.venta_id}" hidden class="btn btn-sm btn-outline-primary mt-3">
                        <i class="fas fa-eye"></i> Ver venta
                    </a>
                </div>
            </div>
        </div>
    `;
                                container.insertAdjacentHTML('beforeend', card);
                            });

                        }

                        $('#modalDetalleCuenta').modal('show');
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("No se pudieron cargar los detalles de la cuenta.");
                    });
            }
        </script>
    @endsection
