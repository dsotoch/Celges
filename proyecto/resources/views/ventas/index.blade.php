@extends('partials.layout')
@section('estilos')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
@endsection
@section('pagina')
    <div class="content-wrapper">
        @component('componentes.com_titulo', [
            'titulo' => 'Gestión de Ventas',
            'paginaprincipal' => 'Ventas',
            'paginaactual' => 'Todas las Ventas',
        ])
        @endcomponent
        <div class="d-flex">
            <div class="col-6 col-md-6 grid-margin ">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-0">
                            Ventas Concretadas {{ \Carbon\Carbon::now('America/Lima')->format('d-m-Y') }}
                        </h4>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-inline-block pt-3">
                                <div class="d-md-flex">
                                    <h2 class="mb-0">{{ $ventas_del_dia->count() }}</h2>
                                    <div class="d-flex align-items-center ml-md-2 mt-2 mt-md-0">
                                        <i class="far fa-clock text-muted"></i>
                                        <small class="ml-1 mb-0">
                                            Actualizado: {{ now('America/Lima')->format('H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="d-inline-block">
                                <i class="fas fa-chart-pie text-info icon-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-6 grid-margin ">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-0">
                            Cotizaciones Pendientes {{ \Carbon\Carbon::now('America/Lima')->format('d-m-Y') }}
                        </h4>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-inline-block pt-3">
                                <div class="d-md-flex">
                                    <h2 class="mb-0">{{ $ventas_del_dia->count() }}</h2>
                                    <div class="d-flex align-items-center ml-md-2 mt-2 mt-md-0">
                                        <i class="far fa-clock text-muted"></i>
                                        <small class="ml-1 mb-0">
                                            Actualizado: {{ now('America/Lima')->format('H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="d-inline-block">
                                <i class="fas fa-chart-pie text-info icon-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex"><button class="btn bg-orange boton" data-toggle="modal" data-target="#tablaModal">
                <i class="fas fa-plus-circle mr-2"></i> Generar Nueva venta
            </button> </div>

        <!---Modal Nueva Venta-->
        <!-- Modal -->
        <div class="modal fade" id="tablaModal" tabindex="-1" role="dialog" aria-labelledby="tablaModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="tablaModalLabel">Cotizaciones para Ventas</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- Campo de búsqueda -->
                        <input type="text" class="form-control mb-3" id="buscarInput" placeholder="Buscar...">

                        <!-- Tabla -->
                        <div class="overflow-auto">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="bg-success text-white">Codigo</th>
                                        <th class="bg-success text-white">Cliente</th>
                                        <th class="bg-success text-white">Destino</th>
                                        <th class="bg-success text-white">Total</th>
                                        <th class="bg-success text-white">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaContenido">
                                    @foreach ($cotizaciones as $item)
                                        <tr>
                                            <td>{{ $item->codigo }}</td>
                                            <td>{{ $item->cliente }}</td>
                                            <td>{{ $item->destino }}</td>
                                            <td>{{ $item->total }}</td>
                                            <td>
                                                <div class="d-flex center gap-2">
                                                    <!-- Botón Ver -->
                                                    <!-- Botón para ABRIR el modal -->
                                                    <!-- Botón Pago -->
                                                    <button class="btn btn-sm btn-success" title="Registrar Pago">
                                                        <i class="fas fa-credit-card"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-info" title="Ver" data-toggle="modal"
                                                        data-target="#modalGenerar"
                                                        onclick="obtenerCotizacion('{{ $item->id }}')">
                                                        <i class="fas fa-eye"></i>
                                                    </button>

                                                    <!-- Botón Anular -->
                                                    <button class="btn btn-sm btn-danger" title="Anular">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>

                </div>
            </div>
        </div>

        <!--final nueva venta-->
        <!---Modal generar Venta-->
        <!-- Modal -->
        <div class="modal fade modal-dialog modal-extra " id="modalGenerar" tabindex="-1" role="dialog"
            aria-labelledby="tablaModalLabel" aria-hidden="true">
            <div class="" role="document">
                <div class="modal-content">
                    <div class="overflow-auto">
                        <div class="border border-danger text-danger p-3 mb-2 hidden blink" id="mensaje_productos">

                        </div>


                        <div class="modal-content cuerpo-coti" id="miDiv">


                            <!---Fin Modal registrar recurso--->
                            <table class="table table-bordered">
                                <thead class="bg-blue-pad text-white">
                                    <tr>
                                        <th colspan="4">
                                            JAMB-TECNOLOGIA - CALIDAD Y GARANTÍA A TU SERVICIO
                                            <br>
                                            <br>
                                            NÚMEROS DE CONTACTO: 916715991 - 916715998
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th class="bg-blue-pad text-12 w-140px text-white ">CLIENTE</th>
                                        <td class="bg-moke"><input type="text" id="cliente"
                                                class="form-control no-border"></td>
                                        <th class="bg-blue-pad text-12 w-140px text-white">FECHA</th>
                                        <td class="w-140px bg-moke" id="fecha">
                                            {{ now('America/Lima')->format('Y-m-d') }}</td>
                                    </tr>

                                    <tr>
                                        <th class="bg-blue-pad text-12 w-140px text-white">DESTINO</th>
                                        <td class="bg-moke"><input type="text" id="destino"
                                                class="form-control no-border"></td>
                                        <th class="bg-blue-pad text-12 w-140px text-white">DOCUMENTO</th>
                                        <td class="w-140px bg-moke" id="codigo">{{ $codigo }}</td>
                                    </tr>

                                </tbody>
                            </table>
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="bg-blue">
                                        <th style="width: 70px" class="text-12">Cantidad</th>
                                        <th class="text-12">Descripcion</th>
                                        <th style="width: 70px" class="text-12">Costo Unitario</th>
                                        <th style="width: 70px" class="text-12">Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody id="detalles">


                                </tbody>
                            </table>
                            <table class=" mt-4  table-full">
                                <tbody>
                                    <tr>
                                        <td colspan="5">
                                            <strong class="text-danger text-12">POLÍTICA DE GARANTÍA, CAMBIOS Y
                                                PRECIOS:</strong>
                                        </td>
                                        <td class="text-12 bg-blue text-white pt-1 pb-1">SUBTOTAL</td>
                                        <td class="resaltado pb-1 pt-1">
                                            <div class="flex text-12">S/
                                                <input type="text" class="text-12 " readonly value="0.00"
                                                    id="subtotal">
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="5">
                                            <textarea class="form-control no-border auto-resize">
- Garantía de 6 meses en todos nuestros equipos, contada a partir de la fecha de compra.
                </textarea>
                                        </td>
                                        <td class="text-12 bg-blue text-white">GASTO DE ENVÍO</td>
                                        <td class="bg-moke">
                                            <div class="montos text-12">S/
                                                <input type="text" class="text-12" value="0.00" id="envio">
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="5" class="text-12">
                                            <textarea class="form-control no-border auto-resize">
- Cambio inmediato dentro de los 3 días siguientes a la compra, si el equipo está en su embalaje original.
                </textarea>
                                        </td>
                                        <td class="text-12 bg-blue text-white">PAGO ENCOMIENDA</td>
                                        <td class="bg-moke">
                                            <div class="montos text-12">S/
                                                <input type="text" class="text-12" value="0.00" id="encomienda">
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="5" class="text-12">
                                            <textarea class="form-control no-border auto-resize">
- No se aceptan devoluciones de equipos.
                </textarea>
                                        </td>
                                        <td class="text-12 bg-blue text-white">SALDO A FAVOR</td>
                                        <td class="bg-moke">
                                            <div class="montos text-12">S/
                                                <input type="text" class="text-12" value="0.00" id="favor">
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="5" class="text-12">
                                            <textarea class="form-control no-border auto-resize">
- No hacemos reintegro de dinero.
                </textarea>
                                        </td>
                                        <td class="text-12 bg-blue text-white">SALDO PENDIENTE</td>
                                        <td class="bg-moke">
                                            <div class="montos text-12">S/
                                                <input type="text" class="text-12" value="0.00" id="pendiente">
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="5" class="text-12">
                                            <textarea class="form-control no-border auto-resize">
- Los precios pueden variar según disponibilidad de stock.
                </textarea>
                                        </td>
                                        <td class="text-12 bg-blue text-white">% FACTURACIÓN</td>
                                        <td class="bg-moke">
                                            <div class="montos text-12">S/
                                                <input type="text" class="text-12" value="0.00" id="facturacion">
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="5" class="text-12">
                                            <input type="text" class="form-control no-border" readonly
                                                value="- Atentamente, JAMB TECHNOLOGY.">
                                        </td>
                                        <td class="text-12 bg-blue text-white pb-2"> TOTAL</td>
                                        <td class="resaltado">
                                            <div class="flex text-12 ">S/
                                                <input type="text" class="text-12" value="0.0" id="total"
                                                    readonly>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                            <hr>
                            <strong class=" text-danger text-12">IMPORTANTE: HORARIOS DE RECEPCION DE PEDIDOS Y
                                PAGOS</strong>

                            <textarea class="form-control no-border auto-resize mt-2">
Para Asegurar una entrega rapida y eficiente a la empresa de carga, es importante tener en cuenta nuestros horarios de recepcion de pedidos y pagos:
                </textarea>
                            <textarea class="form-control no-border auto-resize">
-Recepcion de pedidos: Desde las 10:00 am hasta las 05:30 pm

                </textarea>
                            <textarea class="form-control no-border auto-resize">
-Pago de pedidos: Desde las 10:00 am hasta las 06:00 pm

                </textarea>
                            <textarea class="form-control no-border auto-resize">
-Si tu pedido y  pago son  recibidos dentro de estos horarios, nos esforzaremos por enviar su pedido el mismo dia. De lo contrario, su pedido sera enviado el siguiente dia util.

                </textarea>
                            <textarea class="form-control no-border auto-resize mt-2">
Agradecemos su compresión y cooperacióm en este proceso. Si tiene alguna duda o inquietud, no dude en contactarnos.
                </textarea>



                        </div>


                    </div>
                    <div class="card mt-4">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <!-- Utilidad -->
                            <div>
                                <strong class="text-success h5 mb-0">Utilidad: S/ <span
                                        id="utilidad">0.00</span></strong>
                            </div>

                            <!-- Botón Generar Venta -->
                            <div>
                                <button class="btn btn-primary">
                                    <i class="fas fa-check-circle"></i> Generar Venta
                                </button>
                            </div>
                        </div>


                    </div>

                </div>

            </div>
        </div>





        <!--final generar venta-->
        <div class="card-body">
            @if (session('success-delete'))
                <div class="alert alert-success mb-4 msj">
                    {{ session('success-delete') }}
                </div>
            @endif
            @if ($errors->has('general-error'))
                <div class="alert alert-danger mb-4 msj">
                    {{ $errors->first('general-error') }}
                </div>
            @endif
            @if (session('success_edit'))
                <div class="alert alert-success msj">{{ session('success_edit') }}</div>
            @endif
            @if ($errors->has('general_edit'))
                <div class="alert alert-danger msj">{{ $errors->first('general_edit') }}</div>
            @endif

            {{ $ventas->links() }}
            <br>
            <hr>
            <h4 class="card-title">Lista de Ventas</h4>
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
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
                                                    style="width: 102.688px;">Codigo</th>

                                                <th class="sorting" tabindex="0" aria-controls="order-listing"
                                                    rowspan="1" colspan="1"
                                                    aria-label="Ship to: activate to sort column ascending"
                                                    style="width: 54.6406px;">Marca</th>
                                                <th class="sorting" tabindex="0" aria-controls="order-listing"
                                                    rowspan="1" colspan="1"
                                                    aria-label="Base Price: activate to sort column ascending"
                                                    style="width: 77.5156px;">Modelo</th>
                                                <th class="sorting" tabindex="0" aria-controls="order-listing"
                                                    rowspan="1" colspan="1"
                                                    aria-label="Purchased Price: activate to sort column ascending"
                                                    style="width: 117.828px;">Capacidad</th>

                                                <th class="sorting" tabindex="0" aria-controls="order-listing"
                                                    rowspan="1" colspan="1"
                                                    aria-label="Actions: activate to sort column ascending"
                                                    style="width: 58.75px;">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($ventas as $item)
                                                <tr role="row" class="{{ $loop->odd ? 'odd' : 'even' }}">
                                                    <td class="sorting_1">{{ $item->codigo }}</td>
                                                    <td>{{ $item->marca }}</td>
                                                    <td>{{ $item->modelo }}</td>
                                                    <td>{{ $item->capacidad }}</td>
                                                    <td class="d-flex gap-2 align-items-center">
                                                        <form
                                                            action="{{ route('productos.destroy', ['id' => $item->id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-primary"
                                                                onclick="return eliminar(event,'{{ $item->codigo }}')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>

                                                        <button class="btn btn-outline-primary"
                                                            onclick="llenarParaEditar(this,event)"
                                                            data-id="{{ $item->id }}"
                                                            data-codigo="{{ $item->codigo }}"
                                                            data-tipo="{{ $item->tipo }}"
                                                            data-marca="{{ $item->marca ?? '' }}"
                                                            data-modelo="{{ $item->modelo ?? '' }}"
                                                            data-capacidad="{{ $item->capacidad ?? '' }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>


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
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script src="{{ asset('melody/data-table.js') }}"></script>
    <script>
        document.getElementById('buscarInput').addEventListener('keyup', function() {
            const valor = this.value.toLowerCase();
            const filas = document.querySelectorAll('#tablaContenido tr');

            filas.forEach(fila => {
                const texto = fila.textContent.toLowerCase();
                fila.style.display = texto.includes(valor) ? '' : 'none';
            });
        });

        function obtenerCotizacion(id) {
            $.ajax({
                url: 'cotizacion/' + id,
                type: 'GET',
                success: function(respuesta) {

                    // Limpiar contenido anterior
                    $("#detalles").html('');
                    $("#tb_utilidades").html('');
                    $("#pie-tabla").html('');
                    $("#total").html('');
                    $("#subtotal").html('');
                    $("#envio").html('');
                    $("#encomienda").html('');
                    $("#favor").html('');
                    $("#pendiente").html('');
                    $("#facturacion").html('');
                    $("#utlidad").html('');

                    let total_utilidades = 0;

                    // Llenar datos principales
                    $("#cliente").val(respuesta.cliente);
                    $("#destino").val(respuesta.destino);
                    $("#total").val(respuesta.total);
                    $("#subtotal").val(respuesta.subtotal);
                    $("#envio").val(respuesta.envio);
                    $("#encomienda").val(respuesta.encomienda);
                    $("#favor").val(respuesta.favor);
                    $("#pendiente").val(respuesta.pendiente);
                    $("#facturacion").val(respuesta.facturacion);
                    $("#utilidad").html(respuesta.utilidad);

                    // Iterar productos
                    respuesta.productos.forEach(element => {
                        const cantidad = parseInt(element.cantidad);
                        const precio = parseFloat(element.precio);
                        const producto = element.producto.modelo + " " +
                            element.producto.marca + " " +
                            element.producto.capacidad + " " +
                            (element.registrado == 1 ? "REGISTRADO" : "LIBRE");


                        // Supongamos que el precio_compra está disponible en el producto (ajusta según tu backend)
                        const precio_compra = parseFloat(element.producto.precio_compra ??
                            0); // si no hay, será 0
                        const diferencia = precio - precio_compra;
                        total_utilidades += cantidad * diferencia;

                        // Agregar a detalles de la venta
                        $("#detalles").append(`
                    <tr>
                        <td class="text-center">${cantidad}</td>
                        <td class="text-center">${producto}</td>
                        <td class="text-center">S/ ${precio.toFixed(2)}</td>
                        <td class="text-center">S/ ${(precio * cantidad).toFixed(2)}</td>
                    </tr>
                `);


                    });


                },
                error: function(xhr, status, error) {
                    console.error('Error en la solicitud:', error);
                }
            });
        }
        // Aplica el evento Enter a todos los campos
        ["#envio", "#encomienda", "#favor", "#pendiente", "#facturacion"].forEach(selector => {
            $(selector).on('keydown', function(e) {
                if (e.key === "Enter") {
                    e.preventDefault();

                    let valor = parseFloat($(this).val()) || 0;

                    // Si es #facturacion, tratamos el valor como un porcentaje del subtotal
                    if (selector === "#facturacion") {
                        let subtotal = parseFloat($("#subtotal").val()) || 0;
                        valor = (valor / 100) * subtotal;
                        $(this).val(valor.toFixed(2)); // Reemplazamos con el monto calculado
                    } else {
                        $(this).val(valor.toFixed(2)); // Redondeamos a 2 decimales
                    }

                    calcularTotal();
                }
            });
        });

        function calcularTotal() {
            let subtotal = parseFloat($("#subtotal").val()) || 0;
            let envio = parseFloat($("#envio").val()) || 0;
            let encomienda = parseFloat($("#encomienda").val()) || 0;
            let favor = parseFloat($("#favor").val()) || 0;
            let pendiente = parseFloat($("#pendiente").val()) || 0;
            let facturacion = parseFloat($("#facturacion").val()) || 0;

            let total = subtotal + envio + encomienda + favor + pendiente + facturacion;

            $("#total").val(total.toFixed(2));
        }
        $("#cliente").on("keyup", function() {
            let valor = $(this).val().toUpperCase();
            $(this).val(valor);
        });
        $("#destino").on("keyup", function() {
            let valor = $(this).val().toUpperCase();
            $(this).val(valor);
        });

        $("#generar-imagen").on("click", async function() {

            if (!confirm(
                    "¿Estás seguro de generar la imagen? La cotización se guardará automáticamente al continuar."
                )) {
                return;
            }
            // Mostrar mensaje de carga
            const cargando = $("<div>")
                .attr("id", "mensaje-cargando")
                .text("🖼️ Generando imagen, por favor espera...")
                .css({
                    position: "fixed",
                    top: "20px",
                    left: "50%",
                    transform: "translateX(-50%)",
                    background: "#333",
                    color: "#fff",
                    padding: "10px 20px",
                    borderRadius: "8px",
                    zIndex: 9999,
                    fontSize: "16px",
                });

            $("body").append(cargando);
            const codigo = $("#codigo").text();
            if (await guardarCotizacion() == false) {
                $("#mensaje-cargando").remove();

                return;
            }
            prepararParaCaptura();
            html2canvas(document.getElementById('miDiv'), {
                scale: 3
            }).then(function(canvas) {
                let imgData = canvas.toDataURL('image/png');
                let link = document.createElement('a');
                link.download = 'cotizacion_' + codigo + '.png';
                link.href = imgData;
                link.click();
                $("#mensaje-cargando").remove();
                alert("✅ Imagen Generada y Cotización guardada Correctamente.");
                location.reload();
            });

        });

        async function guardarCotizacion() {
            let respuesta = false;

            const data = {
                cliente: $("#cliente").val().toUpperCase(),
                destino: $("#destino").val(),
                codigo: $("#codigo").text(),
                subtotal: $("#subtotal").val(),
                envio: $("#envio").val(),
                encomienda: $("#encomienda").val(),
                favor: $("#favor").val(),
                pendiente: $("#pendiente").val(),
                facturacion: $("#facturacion").val(),
                total: $("#total").val(),
                utilidad: $("#utilidades").text(),
                cliente_id: cliente_id,
            };

            try {
                await $.ajax({
                    url: "/cotizacion/update",
                    method: "POST",
                    data: JSON.stringify(data),
                    contentType: "application/json",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        respuesta = true;
                    },
                    error: function(xhr) {
                        console.error("❌ Error al guardar la cotización:", xhr);
                        if (xhr.responseJSON) {
                            console.error("📄 Respuesta JSON:", xhr.responseJSON);
                            alert("Error: " + (xhr.responseJSON.message ||
                                "Error desconocido."));
                        } else {
                            alert("❌ Error al guardar. Revisa la consola.");
                        }
                        respuesta = false;
                    }
                });
            } catch (error) {
                respuesta = false;
            }

            return respuesta;
        }
    </script>
@endsection
