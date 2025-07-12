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
                    </ul>

                    <div class="tab-content pt-3" id="myTabContent">
                        <!-- Proveedores -->
                        <div class="tab-pane fade show active" id="proveedores" role="tabpanel">
                            <table class="table table-bordered datatable">
                                <thead>
                                    <tr>
                                        <th>Proveedor</th>
                                        <th>Monto Total</th>
                                        <th>Pagado</th>
                                        <th>Pendiente</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @forelse ($compras as $compra)
                                        <tr>
                                            <td>{{ $compra->persona->nombres }}</td>
                                            <td>{{ number_format($compra->total, 2) }}</td>
                                            <td>{{ number_format($pagos[$compra->id]->sum('monto_pagado'), 2) }}</td>
                                            <td>{{ number_format($compra->total - $pagos[$compra->id]->sum('monto_pagado'), 2) }}
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-success btnregistrarpago" title="Pagar"
                                                    data-toggle="modal" data-target="#registroPagoModal"
                                                    data-id="{{ $compra->id }}" data-cliente="{{ $compra->persona->id }}"
                                                    data-total="{{ $compra->total - $pagos[$compra->id]->sum('monto_pagado') }}">
                                                    <i class="fas fa-money-bill-wave"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No hay Deudas Pendientes.
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                        <!-- Modal  registrar Pago -->
                        <div class="modal fade " id="registroPagoModal" tabindex="-1" role="dialog"
                            aria-labelledby="modalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">

                                <div class="modal-content">
                                    <div class="modal-header bg-orange text-white">
                                        <h5 class="modal-title" id="modalLabel"><i
                                                class="fas fa-money-check-alt mr-2"></i>Registrar
                                            Pagos. Total: <span id="n_ventapago"></span></h5>
                                        <button type="button" class="close text-white" data-dismiss="modal"
                                            aria-label="Cerrar">
                                            <span aria-hidden="true" class="text-white">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('pagos.create') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="cliente_id" id="cliente_id">
                                        <input type="hidden" name="compra_id" id="compra_id">

                                        <div class="modal-body">

                                            <!-- Método de Pago -->
                                            <div class="form-group metodo_pago">
                                                <label for="metodo_pago"><i
                                                        class="fas fa-credit-card mr-1 text-black"></i>Método
                                                    de Pago</label>
                                                <select class="form-control" id="metodo_pago">
                                                    <option value="">Seleccione un método</option>
                                                    <option value="Transferencia">Transferencia</option>

                                                    <option value="Efectivo">Efectivo</option>
                                                </select>
                                            </div>
                                            <!-- Banco -->
                                            <div class="form-group oculto banco">
                                                <label for="banco"><i
                                                        class="fas fa-university mr-1 text-black"></i>Banco</label>
                                                <select class="form-control" id="banco">
                                                    <option value="">Seleccione un banco</option>
                                                    @foreach ($cuentasbancos as $item)
                                                        <option value="{{ $item->id }}">
                                                            {{ $item->banco }}--{{ $item->tipo_cuenta }}--{{ $item->moneda }}--{{ $item->titular }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </div>

                                            <!-- N° de Operación -->
                                            <div class="form-group oculto operacion">
                                                <label for="numero_operacion"><i
                                                        class="fas fa-receipt mr-1 text-black"></i>N° de
                                                    Operación</label>
                                                <input type="text" class="form-control" id="numero_operacion">
                                            </div>

                                            <!-- Monto -->
                                            <div class="form-group oculto monto">
                                                <label for="monto"><i class="fas fa-coins mr-1 text-black"></i>Monto
                                                    (S/)</label>
                                                <input type="number" step="0.01" class="form-control" name="monto"
                                                    id="monto">
                                            </div>

                                            <hr>
                                            <div class="oculto detallespagos">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h5 class="mb-0">💳 Detalles de Pago</h5>
                                                    <button class="btn btn-primary" type="button"
                                                        id="btnagregarPago">Agregar</button>
                                                </div>

                                                <div class="card" id="cuerpopagos">

                                                </div>

                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success"
                                                onclick="return GuardarPagos(event);">
                                                <i class="fas fa-save mr-1"></i>Guardar
                                            </button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>


                        <!-- Gastos Fijos -->
                        <div class="tab-pane fade" id="gastos" role="tabpanel">

                            <form method="POST" action="{{ route('pagos.crearservicio') }}">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="servicio">Servicio:</label>
                                        <select name="servicio" id="servicio" class="form-control" required>
                                            <option value="">-- Selecciona un servicio --</option>
                                            @foreach ($servicios->slice(2) as $item)
                                                <option value="{{ $item->id }}" title="{{ $item->descripcion }}">
                                                    {{ $item->nombre }}</option>
                                            @endforeach

                                            <!-- Agrega más servicios si es necesario -->
                                        </select>
                                    </div>

                                    <div class="col-md-8">
                                        <label for="nota">Nota:</label>
                                        <textarea name="nota" id="nota" class="form-control" rows="3"
                                            placeholder="Escribe una descripción del gasto..."></textarea>
                                    </div>
                                    <br>
                                    <div class="col-md-6">
                                        <label for="fecha">Fecha de Pago:</label>
                                        <input type="date" id="fecha" name="fecha" class="form-control"
                                            required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="monto">Monto:</label>
                                        <input type="number" id="monto" name="monto" min="0"
                                            step="0.01" class="form-control" required>
                                    </div>
                                </div>
                                @if (session('success_servicio'))
                                    <div class="alert alert-success mb-2 mt-2 msj">
                                        {{ session('success_servicio') }}
                                    </div>
                                @endif
                                @if ($errors->has('error_servicio'))
                                    <div class="alert alert-danger mb-2 mt-2 msj">
                                        <ul class="mb-0">
                                            <li>{{ $errors->first('error_servicio') }}</li>
                                        </ul>
                                    </div>
                                @endif


                                <div class="text-end mb-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Guardar Gasto
                                    </button>
                                </div>
                            </form>
                            <hr>
                            <!-- Tabla de gastos -->
                            <table class="table table-bordered datatable">
                                <thead>
                                    <tr>
                                        <th>Tipo de Gasto</th>
                                        <th>Descripción</th>
                                        <th>Monto</th>
                                        <th>Fecha</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pendientes as $gasto)
                                        <tr>
                                            <td>{{ $gasto->servicio->nombre }}</td>
                                            <td>{{ $gasto->nota ?? $gasto->servicio->descripcion }}</td>
                                            <td>{{ number_format($gasto->monto_pagado, 2) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($gasto->fecha_pago)->format('Y-m-d') }}</td>
                                            <td class="d-flex center gap-2">
                                                <button class="btn btn-sm btn-success btnregistrarpagoServicio"
                                                    title="Pagar" data-toggle="modal" data-target="#registroPagoModal"
                                                    data-id="{{ $gasto->id }}"
                                                    data-total="{{ $gasto->monto_pagado }}">

                                                    <i class="fas fa-money-bill-wave"></i>
                                                </button>
                                                <form action="{{ route('pagos.destroy', ['id' => $gasto->id]) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger" title="Eliminar Registro"
                                                        onclick="return EliminarPago(event);">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>



                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No hay gastos registrados.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    @endsection
    @section('scripts')
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

                if (tabGastos.classList.contains("show") && tabGastos.classList.contains("active")) {
                    total = totaldeudaServicio;

                } else {
                    total = totaldeuda;
                }
                if (confirmacion) {
                    let response = await calcularPagos(total);
                    if (response == "ok") {
                        event.target.closest("form").submit();

                    }
                }
            }
            async function calcularPagos(total) {
                let divpagos = $("#cuerpopagos");
                let totalmontos = 0.00;
                let montos = divpagos.find("[name='monto[]']");

                montos.each(function() {
                    const valor = parseFloat($(this).val()) || 0;
                    totalmontos += valor;
                });
                const tabGastos = document.getElementById("gastos");


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
