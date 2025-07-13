@extends('partials.layout')
@section('estilos')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
@endsection
@section('pagina')
    <div class="content-wrapper ">
        @component('componentes.com_titulo', [
            'titulo' => 'Gestión de Cuentas',
            'paginaprincipal' => 'Cuentas',
            'paginaactual' => 'Cuentas de mis Clientes',
        ])
        @endcomponent

        <div class="container mt-4">
            <div class="card shadow-lg">
                <div class="card-header bg-orange text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-friends"></i> Todas las Cuentas de Clientes
                    </h5>

                </div>
                @if (session('success'))
                    <div class="alert alert-success mb-4 msj">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive p-3">
                            <div id="order-listing_wrapper"
                                class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="order-listing" class="table dataTable no-footer" role="grid"
                                            aria-describedby="order-listing_info">
                                            <thead>
                                                <tr role="row">

                                                    <th class="sorting" tabindex="0" aria-controls="order-listing"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Purchased On: activate to sort column ascending"
                                                        style="width: 102.688px;">Cliente</th>

                                                    <th class="sorting" tabindex="0" aria-controls="order-listing"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Ship to: activate to sort column ascending"
                                                        style="width: 54.6406px;">Telefono</th>
                                                    <th class="sorting" tabindex="0" aria-controls="order-listing"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Base Price: activate to sort column ascending"
                                                        style="width: 77.5156px;">Monto Deuda</th>

                                                    <th class="sorting" tabindex="0" aria-controls="order-listing"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Actions: activate to sort column ascending"
                                                        style="width: 58.75px;">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($cuentas as $item)
                                                    <tr>
                                                        <td>{{ $item->nombres }}</td>
                                                        <td>{{ $item->telefono }}</td>
                                                        <td>{{ $item->total_deuda }}</td>
                                                        <td class="text-center">
                                                            <button class="btn btn-sm btn-info" title="Ver"
                                                                onclick="detallesDeuda('{{ $item->cliente_id }}')">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                           @hasrole("admin")
                                                            <button class="btn btn-sm btn-success btnregistrarpago" title="Pagar"
                                                                data-toggle="modal"
                                                                data-target="#registroPagoModal"
                                                                data-id={{ $item->cliente_id }}
                                                                data-total={{ $item->total_deuda }}>
                                                                <i class="fas fa-money-bill-wave"></i>
                                                            </button>
                                                           @endhasrole
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
                </div>
            </div>
        </div>
        <!-- Modal  registrar Pago -->
        <div class="modal fade " id="registroPagoModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">

                <div class="modal-content">
                    <div class="modal-header bg-orange text-white">
                        <h5 class="modal-title" id="modalLabel"><i class="fas fa-money-check-alt mr-2"></i>Registrar
                            Pagos. Total: <span id="n_ventapago"></span></h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true" class="text-white">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('pagos.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="cliente_id" id="cliente_id">
                        <div class="modal-body">

                            <!-- Método de Pago -->
                            <div class="form-group metodo_pago">
                                <label for="metodo_pago"><i class="fas fa-credit-card mr-1 text-black"></i>Método
                                    de Pago</label>
                                <select class="form-control" id="metodo_pago">
                                    <option value="">Seleccione un método</option>
                                    <option value="Transferencia">Transferencia</option>

                                    <option value="Efectivo">Efectivo</option>
                                </select>
                            </div>
                            <!-- Banco -->
                            <div class="form-group oculto banco">
                                <label for="banco"><i class="fas fa-university mr-1 text-black"></i>Banco</label>
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
                                <label for="numero_operacion"><i class="fas fa-receipt mr-1 text-black"></i>N° de
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
                                    <button class="btn btn-primary" type="button" id="btnagregarPago">Agregar</button>
                                </div>

                                <div class="card" id="cuerpopagos">

                                </div>

                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" onclick="return GuardarPagos(event);">
                                <i class="fas fa-save mr-1"></i>Guardar
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modalDetalleCuenta" tabindex="-1" role="dialog"
            aria-labelledby="modalDetalleCuentaLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header bg-orange text-white">
                        <h5 class="modal-title" id="modalDetalleCuentaLabel"><i class="fas fa-receipt"></i> Detalles de
                            la
                            Cuenta</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="card border-primary">
                            <div id="cards-detalles-cuentas" class="row">
                                <!-- Aquí irán los cards generados -->
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times-circle"></i> Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('scripts')
        <script src="{{ asset('melody/data-table.js') }}"></script>



        <script>
            let totaldeuda = "";
            if ($(".msj").length) {
                setTimeout(() => {
                    $(".msj").fadeOut();

                }, 3000);
            }
            async function GuardarPagos(event) {
                event.preventDefault();
                let confirmacion = confirm(
                    "⚠️ ¿Estás seguro de guardar los Pagos. Revisa bien los datos antes de confirmar.?");
                let total = totaldeuda;
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

                if (true) {
                    let montos = divpagos.find("[name='monto[]']");

                    montos.each(function() {
                        const valor = parseFloat($(this).val()) || 0;
                        totalmontos += valor;
                    });

                    if (totalmontos === 0.00) {
                        alert("💰 Ingresa un monto válido. No hay detalles de pago.");
                        return "error";
                    }

                    if (totalmontos > total) {
                        let resp = confirm(
                            "💰 La suma de todos los pagos es mayor al total de la deuda."
                        );
                        return  "error";
                    }
                }
                return "ok";
            }
            $(".btnregistrarpago").on("click", function() {
                let total = this.dataset.total;
                let id = this.dataset.id;
                totaldeuda = total;
                $("#n_ventapago").html("S/" + total);
                $("#cliente_id").val(id);
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
                }else{
                    operacion="0";
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
