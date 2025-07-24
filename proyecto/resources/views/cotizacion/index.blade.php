@extends('partials.layout')
@section('estilos')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
@endsection
@section('pagina')
    <div class="content-wrapper ">
        @component('componentes.com_titulo', [
            'titulo' => 'Gestión de Cotizaciones',
            'paginaprincipal' => 'Cotizaciones',
            'paginaactual' => 'Cotizaciones',
        ])
        @endcomponent


        <div class="overflow-auto">

            <div>
                <div class="border border-danger text-danger p-3 mb-2 hidden blink" id="mensaje_productos">
                    <p><b>Cliente:</b> <span id="mensajecliente"></span></p>
                    <p class="mt-2"><b>Numero:</b> <span>{{ $codigo }}</span></p>
                    <p class="mt-2"><b>Fecha:</b> <span>{{ now('America/Lima')->format('Y-m-d') }}</span></p>


                </div>
                <button class="hidden" id="imprimirMensaje"
                    style="
        cursor: pointer;
        border: 1px solid #007bff;
        background-color: #ffffff;
        color: #007bff;
        padding: 8px 16px;
        border-radius: 4px;
        margin-top: 4px;
        margin-bottom: 12px;
        transition: background-color 0.3s, color 0.3s;
    "
                    onmouseover="this.style.backgroundColor='#007bff'; this.style.color='white';"
                    onmouseout="this.style.backgroundColor='white'; this.style.color='#007bff';">
                    Generar Imagen Mensaje
                </button>
            </div>


            <div class="modal-content cuerpo-coti" id="miDiv">


                <!---Fin Modal registrar recurso--->
                <table class="table table-bordered">
                    <thead class="bg-blue-pad text-white">
                        <tr>
                            <th colspan="4">
                                JAMB TECHNOLOGY - CALIDAD Y GARANTÍA A TU SERVICIO
                                <br>
                                <br>
                                NÚMEROS DE CONTACTO:
                                <span>{{ $numeros['numero1'] }}</span><span>-{{ $numeros['numero2'] }}</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th class="bg-blue-pad text-12 w-140px text-white ">CLIENTE</th>
                            <td class="bg-moke"><input type="text" id="cliente" class="form-control no-border"></td>
                            <input type="hidden" id="idcliente">
                            <th class="bg-blue-pad text-12 w-140px text-white">FECHA</th>
                            <td class="w-140px bg-moke">{{ now()->format('Y-m-d') }}</td>
                        </tr>

                        <tr>
                            <th class="bg-blue-pad text-12 w-140px text-white">DESTINO</th>
                            <td class="bg-moke"><input type="text" id="destino" class="form-control no-border"></td>
                            <th class="bg-blue-pad text-12 w-140px text-white">DOCUMENTO</th>
                            <td class="w-140px bg-moke" id="codigo">#{{ $codigo }}</td>
                        </tr>

                    </tbody>
                </table>
                <table class="table table-bordered">
                    <thead>
                        <tr class="bg-blue">
                            <th style="width: 70px" class="text-12">Cantidad</th>
                            <th class="text-12">Descripcion</th>
                            <th style="width: 50px" class="text-12">Costo por Registro</th>
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
                                    <input type="text" class="text-12 " readonly value="0.00" id="subtotal">
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
                            <td colspan="5"></td>
                            <td class="text-12 bg-blue text-white p-2">REGISTRO DE EQUIPO</td>
                            <td class="bg-moke">
                                <div class="flex text-12 ">S/
                                    <input type="text" class="text-12" value="0.00" id="totalregistro" readonly>
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
                                    <input type="text" class="text-12" value="0.0" id="total" readonly>
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
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <td class="bg-success text-white">Numero en la Lista</td>
                        <td class="bg-success text-white">Precio de Compra</td>
                        <td class="bg-success text-white">Utilidad</td>
                        <td class="bg-success text-white">Total</td>
                    </thead>
                    <tbody id="tb_utilidades">

                    </tbody>
                    <tfoot id="pie-tabla">

                    </tfoot>
                </table>
            </div>

        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalCotizacion" tabindex="-1" role="dialog" aria-labelledby="modalCotizacionLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCotizacionLabel"><i class="fas fa-file-invoice"></i> Productos en
                        Almacen</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body overflow-auto">
                    <div class="form-group">
                        <label for="buscador">Buscar</label>
                        <input type="search" name="buscador" id="buscador" class="form-control"
                            placeholder="Ingrese texto a buscar">
                    </div>
                    <table class="table datatable  table-bordered table-sm">
                        <thead class="thead-p bg-success">
                            <tr>
                                <th>Fecha de Compra</th>
                                <th>Producto</th>
                                <th>IMEI</th>
                                <th>Proveedor</th>
                                <th>Precio Compra (S/)</th>
                            </tr>
                        </thead>
                        @php
                            $preciosUnificados = [];

                            foreach ($almacen as $item) {
                                $claveProducto =
                                    $item->producto->marca .
                                    ' ' .
                                    $item->producto->modelo .
                                    ' ' .
                                    $item->producto->capacidad .
                                    ' ' .
                                    strtoupper($item->color) .
                                    ' ' .
                                    ($item->registrado == 1 ? 'REGISTRADO' : 'LIBRE');

                                if (!isset($preciosUnificados[$claveProducto])) {
                                    $preciosUnificados[$claveProducto] = ['total' => 0, 'count' => 0];
                                }

                                $preciosUnificados[$claveProducto]['total'] += $item->precio_compra;
                                $preciosUnificados[$claveProducto]['count']++;
                            }

                            // Calcular promedio
                            foreach ($preciosUnificados as $clave => $data) {
                                $preciosUnificados[$clave] = round($data['total'] / $data['count'], 2);
                            }
                        @endphp

                        <tbody id="datos-productos">
                            @foreach ($almacen as $item)
                                @php
                                    $claveProducto =
                                        $item->producto->marca .
                                        ' ' .
                                        $item->producto->modelo .
                                        ' ' .
                                        $item->producto->capacidad .
                                        ' ' .
                                        strtoupper($item->color) .
                                        ' ' .
                                        ($item->registrado == 1 ? 'REGISTRADO' : 'LIBRE');

                                    $precioUnificado = $preciosUnificados[$claveProducto] ?? $item->precio_compra;
                                @endphp

                                <tr class="selectable-row {{ $item->cantidad == 0 ? 'bgred' : '' }}"
                                    data-id="{{ $item->producto->id }}"data-color="{{ $item->color }}"
                                    data-imei="{{ $item->imei }}" data-cantidad="{{ $item->cantidad }}"
                                    data-registrado="{{ $item->registrado }}" data-precio="{{ $precioUnificado }}"
                                    data-producto="{{ $claveProducto }}">

                                    <td>{{ $item->compra->fecha_compra }}</td>
                                    <td>{{ $claveProducto }}</td>
                                    <td>{{ $item->imei }}</td>
                                    <td>{{ $item->compra->persona->nombres }}</td>
                                    <td>{{ $precioUnificado }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="enviar"><i class="fas fa-paper-plane"></i>
                        Enviar a
                        Cotización</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal  Clientes-->
    <div class="modal fade" id="modalCotizacionCliente" tabindex="-1" role="dialog"
        aria-labelledby="modalCotizacionLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCotizacionLabel"><i class="fas fa-file-invoice"></i> Lista de
                        Clientes</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body overflow-auto">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-p bg-success">
                            <tr>
                                <th>Nombres</th>
                                <th>Direccion</th>
                                <th>Telefono</th>
                                <th>Email</th>
                            </tr>
                        </thead>


                        <tbody id="datos-clientess">
                            @foreach ($clientes as $cl)
                                <tr class="selectable-rowCliente" data-id="{{ $cl->id }}"
                                    data-cliente="{{ e($cl->nombres) }}" data-direccion="{{ e($cl->direccion) }}">
                                    <td>{{ $cl->nombres }}</td>
                                    <td>{{ $cl->direccion ?? '-' }}</td>
                                    <td>{{ $cl->telefono }}</td>
                                    <td>{{ $cl->email }}</td>
                                </tr>
                            @endforeach


                        </tbody>
                    </table>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="enviarCliente"><i class="fas fa-paper-plane"></i>
                        Enviar a
                        Cotización</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

    <script>
        document.getElementById('buscador').addEventListener('input', function() {
            const filtro = this.value.toLowerCase();
            const filas = document.querySelectorAll('table.datatable tbody tr');

            filas.forEach(fila => {
                // Concatenar el texto de todas las celdas de la fila
                const textoFila = fila.textContent.toLowerCase();
                if (textoFila.indexOf(filtro) > -1) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
        });

        $(document).ready(function() {
            let restarsaldo = false;
            const body = $('body');

            if ((body.hasClass('sidebar-toggle-display')) || (body.hasClass('sidebar-absolute'))) {
                body.toggleClass('sidebar-hidden');
            } else {
                body.toggleClass('sidebar-icon-only');
            }

            let productos = [];
            let id = "";
            let producto = "";
            let registrado = '';
            let precio_compra = "";
            let total_utilidades = 0.00;
            let color = "";
            // Selección de fila
            $('.selectable-row').on('click', function() {
                $('.selectable-row').removeClass('selected');
                $(this).addClass('selected');
                id = $(this).data('id');
                producto = $(this).data('producto');
                color = $(this).data('color');
                registrado = $(this).data('registrado');
                precio_compra = $(this).data('precio');

            });

            let idClienteTabla = null;
            let clienteTabla = null;
            let direccionClienteTabla = null;
            $('.selectable-rowCliente').on('click', function() {
                $('.selectable-rowCliente').removeClass('selected');
                $(this).addClass('selected');
                idClienteTabla = $(this).data('id');
                clienteTabla = $(this).data('cliente');
                direccionClienteTabla = $(this).data('direccion');
            });
            $('#enviarCliente').on('click', function() {
                enviarTablaCliente();
            });

            async function enviarTablaCliente() {
                if (!clienteTabla) {
                    alert("Seleccione un Cliente.");
                    return;
                }
                document.getElementById("cliente").value = clienteTabla;
                document.getElementById("mensajecliente").textContent = clienteTabla;
                document.getElementById("idcliente").value = idClienteTabla;
                document.getElementById("destino").value = direccionClienteTabla;
                $("#modalCotizacionCliente").modal("hide");
                await vercuentasCliente(idClienteTabla);
                await verSaldoAFavorCliente(idClienteTabla);
            }
            async function vercuentasCliente(clienteId) {
                try {
                    const response = await fetch(`cuentas/saldoPendiente/${clienteId}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        throw new Error(`Error HTTP: ${response.status}`);
                    }

                    const data = await response.json();
                    if (data.saldo > 0) {
                        alert("ℹ️ El cliente tiene un saldo pendiente.");
                        $("#pendiente").val((data.saldo).toFixed(2));
                        calcularTotal();
                    }

                } catch (error) {
                    console.error("❌ Error al obtener las cuentas del cliente:", error);
                }
            }
            async function verSaldoAFavorCliente(clienteId) {
                try {
                    const response = await fetch(`compras/saldo-favor/${clienteId}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        throw new Error(`Error HTTP: ${response.status}`);
                    }

                    const data = await response.json();
                    if (data.mensaje > 0) {
                        if (confirm(
                                "ℹ️ El cliente tiene un saldo a Favor.¿Deseas que se Reste en el Total de la Deuda?"
                            )) {
                            restarsaldo = true;
                        }
                        $("#favor").val((data.mensaje).toFixed(2));
                        calcularTotal();
                    }

                } catch (error) {
                    console.error("❌ Error al obtener las cuentas del cliente:", error);
                }
            }

            // Evento botón enviar
            $('#enviar').on('click', function() {
                let existe = productos.some(p => p.id === id && p.color == color && p.registrado ==
                    registrado);
                if (existe) {
                    alert("Este Producto ya esta Agregado en la Cotización");
                    return;
                }
                enviarTabla();
            });


            async function enviarTabla() {
                if (!producto) {
                    alert("Seleccione un producto.");
                    return;
                }

                let cantidad = prompt("Ingrese la cantidad", "1");
                if (!cantidad || isNaN(cantidad) || cantidad <= 0) {
                    alert("Ingrese una cantidad válida.");
                    return;
                }

                let precio = prompt("Ingrese el precio de venta");
                if (!precio || isNaN(precio) || precio <= 0) {
                    alert("Ingrese un precio válido.");
                    return;
                }

                alert("Producto Agregado Correctamente");

                cantidad = parseInt(cantidad);
                precio = parseFloat(precio);
                color = color;

                await listarEnAlmacenInterno(id, color, registrado, cantidad);

                $("#detalles").append(`
    <tr>
        <td class="text-center" class='tdcantidad'>${cantidad}</td>
        <td class="text-center"><input class='form-control no-border' style='text-align:center;' type='text' value='${producto}'/></td>
        <td class="text-center"><input  class='costoregistro text-center' value='0.00'/></td>

        <td class="text-center">${precio.toFixed(2)}</td>
        <td class="text-center">${(cantidad * precio).toFixed(2)}</td>
    </tr>
`);
                let diferencia = precio - precio_compra;
                total_utilidades += cantidad * diferencia;
                $("#tb_utilidades").append(`
    <tr>
        <td class="text-center">${producto}</td>
        <td class="text-center">${precio_compra.toFixed(2)}</td>
        <td class="text-center">${diferencia.toFixed(2)}</td>
        <td class="text-center">${(cantidad * diferencia).toFixed(2)}</td>
    </tr>
`);
                $("#pie-tabla").html(`
  <tr>
    <td colspan='3' class="text-end"><strong>Total</strong></td>
    <td class="text-end"><strong>S/</strong><strong id='utilidades'> ${total_utilidades.toFixed(2)}</strong></td>
  </tr>
`);


                agregarProducto(id, cantidad, precio, registrado, color);
                calcularsubtotal();
                cantidad = "";
                precio = "";
                id = "";
                registrado = "";
                producto = "";
                precio_compra = "";
                diferencia = "";
                color = "";

            }
            $("#detalles").on('keydown', '.costoregistro', function(e) {
                if (e.key === "Enter" || e.keyCode === 13) {
                    e.preventDefault();
                    const $fila = $(this).closest("tr");

                    const primerTD = $(this).closest("tr").children("td").first();
                    const cantidad = primerTD.text();
                    const costo = parseFloat($(this).val()) || 0;
                    const subtotal = cantidad * costo;
                    const subTotalActual = $(this).closest("td").next();
                    const tdActual = $(this).closest("td").next().next();
                    const totalActual = parseFloat(tdActual.text()) || 0;

                    const total = totalActual + subtotal;

                    if (costo > 0) {
                        tdActual.text(total.toFixed(2));
                        $(this).val(subtotal);

                    } else {
                        tdActual.text((parseFloat(subTotalActual.text()) * cantidad).toFixed(2));


                    }
                    calcularsubtotal();
                }
            });

            async function listarEnAlmacenInterno(producto_id, color, registrado, cantidad) {
                let cuantos_almacen = 0;

                $("#datos-productos tr").each(function() {
                    const dataset = this.dataset;

                    if (dataset.producto === producto) {
                        const cantidad = parseFloat(dataset.cantidad) || 0;
                        cuantos_almacen += cantidad;
                    }
                });
                let diferencia = Number(cuantos_almacen);

                if (Number(cantidad) > diferencia) {
                    let dif = Number(cantidad) - diferencia;
                    $("#mensaje_productos").removeClass("hidden").show();
                    $("#imprimirMensaje").removeClass("hidden").show();
                    $("#mensaje_productos").append(`
    <p> Faltan ${dif} existencias para Despachar, para el producto ${producto} </p>
    <hr>
`);
                }
            }

            function agregarProducto(id, cantidad, precio, registrado, color) {
                // Crear un objeto producto
                let item = {
                    id: id,
                    cantidad: parseInt(cantidad),
                    color: color,
                    precio: parseFloat(precio),
                    registrado: parseInt(registrado)
                };

                // Agregarlo al arreglo
                productos.push(item);
            }

            function calcularsubtotal() {
                let total = 0;

                $("#detalles tr").each(function() {
                    let valorTexto = $(this).find("td").eq(4).text().trim();
                    let valor = parseFloat(valorTexto);
                    if (!isNaN(valor)) {
                        total += valor;
                    }
                });
                let totalRegistro = 0.00;
                $("#detalles tr").each(function() {
                    let valorTexto = $(this).find("td").eq(2).find("input").val().trim();
                    let valor = parseFloat(valorTexto);
                    if (!isNaN(valor)) {
                        totalRegistro += valor;
                    }
                });
                $("#totalregistro").val(totalRegistro.toFixed(2));
                const diferencia = total - totalRegistro;
                $("#subtotal").val(diferencia.toFixed(2));
                calcularTotal();
            }

            function calcularTotal() {
                let subtotal = parseFloat($("#subtotal").val()) || 0;
                let totalregistro = parseFloat($("#totalregistro").val()) || 0;

                let envio = parseFloat($("#envio").val()) || 0;
                let encomienda = parseFloat($("#encomienda").val()) || 0;
                let favor = parseFloat($("#favor").val()) || 0;
                let pendiente = parseFloat($("#pendiente").val()) || 0;
                let facturacion = parseFloat($("#facturacion").val()) || 0;
                let total = 0.00;
                if (restarsaldo) {
                    let totalsinSaldoFavor = subtotal + envio + encomienda + facturacion + totalregistro;
                    if (favor >= totalsinSaldoFavor) {
                        total = 0.00;
                    } else {
                        total = subtotal + envio - favor + encomienda + facturacion + totalRegistro;
                    }

                } else {
                    total = subtotal + envio + encomienda + facturacion + totalregistro;

                }

                $("#total").val(total.toFixed(2));
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
                            $(this).val(valor.toFixed(
                                2)); // Reemplazamos con el monto calculado
                        } else {
                            $(this).val(valor.toFixed(2)); // Redondeamos a 2 decimales
                        }

                        calcularTotal();
                    }
                });
            });

            function prepararParaCaptura() {

                document.querySelectorAll("input[readonly], input, textarea").forEach(el => {
                    const p = document.createElement("p");

                    // Copiar contenido
                    p.textContent = el.value;

                    // Copiar estilos esenciales manualmente
                    const computed = getComputedStyle(el);
                    const estilosClaves = [
                        "font", "color", "backgroundColor", "padding", "margin", "border",
                        "borderRadius", "display", "width", "height", "textAlign", "whiteSpace"
                    ];

                    estilosClaves.forEach(prop => {
                        p.style[prop] = computed[prop];
                    });

                    // Asegurar saltos de línea si es textarea
                    p.style.whiteSpace = "pre-wrap";

                    // Reemplazar el elemento original
                    el.parentNode.replaceChild(p, el);
                });






            }
            $("#cliente").on("keyup", function() {
                let valor = $(this).val().toUpperCase();
                $(this).val(valor);
                $("#mensajecliente").text(valor);
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
                    totalregistro:$("#totalregistro").val(),
                    nota: $("#mensaje_productos").text(),
                    total: $("#total").val(),
                    utilidad: $("#utilidades").text(),
                    persona_id: document.getElementById("idcliente").value,
                    productos: productos // asegúrate que esto exista
                };


                try {
                    await $.ajax({
                        url: "/cotizacion/guardar",
                        method: "POST",
                        data: JSON.stringify(data),
                        contentType: "application/json",
                        dataType: "json", // ayuda a interpretar la respuesta correctamente
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



        });
        $("#imprimirMensaje").on("click", function() {
            if (document.getElementById("cliente").value == "") {
                alert("💢Ingresa el Cliente");
                return;
            }
            html2canvas(document.getElementById('mensaje_productos'), {
                scale: 3
            }).then(function(canvas) {
                let imgData = canvas.toDataURL('image/png');
                let link = document.createElement('a');
                link.download = `mensaje-${$("#codigo").text()}.png`;
                link.href = imgData;
                link.click();
                alert("✅ Imagen Generada Correctamente.");
            });

        })
    </script>

    <script src="{{ asset('melody/data-table.js') }}"></script>
@endsection
