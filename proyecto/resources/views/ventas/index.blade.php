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
                            Cotizaciones Pendientes
                        </h4>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-inline-block pt-3">
                                <div class="d-md-flex">
                                    <h2 class="mb-0">{{ $cotizaciones->count() }}</h2>
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

                                                    <button class="btn btn-sm btn-info" title="Ver"
                                                        onclick="obtenerCotizacion('{{ $item->id }}')">
                                                        <i class="fas fa-eye"></i>
                                                    </button>

                                                    <!-- Botón Anular -->
                                                    <form action="{{ route('cotizacion.update', ['id' => $item->id]) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('PUT')
                                                        <button class="btn btn-sm btn-danger" type="button"
                                                            onclick="anular(event,'{{ $item->codigo }}')" title="Anular">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach




                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-black" data-dismiss="modal">Cerrar</button>
                    </div>

                </div>
            </div>
        </div>

        <!--final nueva venta-->
        <!---Inicio Modal registrar proveedor--->
        <div class="modal fade" id="modalProveedor" tabindex="-1" z-index="50" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Registrar Cliente</h5>
                        <button type="button" class="close" onclick="cerrarYMostrar()">
                            <span>X</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="modal-body">
                            <div class="row">
                                <!-- Código -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="codigo">Código</label> <span class="obligatorio">*</span>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-code"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="codigo" id="codigopersona"
                                            class="form-control @error('codigo') is-invalid @enderror"
                                            value="{{ old('codigo', $codigopersona) }}" placeholder="Ingrese código"
                                            readonly>

                                    </div>
                                </div>

                                <!-- Nombres -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="nombres">Nombres</label> <span class="obligatorio">*</span>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-user"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="nombres" id="nombres"
                                            class="form-control @error('nombres') is-invalid @enderror"
                                            value="{{ old('nombres') }}" placeholder="Ingrese nombre del proveedor">

                                    </div>
                                </div>

                                <!-- RUC -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="ruc">RUC</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-id-card"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="ruc" id="ruc"
                                            class="form-control @error('ruc') is-invalid @enderror"
                                            value="{{ old('ruc') }}" placeholder="Ingrese RUC">

                                    </div>
                                </div>

                                <!-- Dirección -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="direccion">Dirección</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="direccion" id="direccion"
                                            class="form-control @error('direccion') is-invalid @enderror"
                                            value="{{ old('direccion') }}" placeholder="Ingrese dirección">

                                    </div>
                                </div>

                                <!-- Teléfono -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="telefono">Teléfono</label> <span class="obligatorio">*</span>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-phone"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="telefono" id="telefono"
                                            class="form-control @error('telefono') is-invalid @enderror"
                                            value="{{ old('telefono') }}" placeholder="Ingrese teléfono">

                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="email">Email</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-envelope"></i>
                                            </span>
                                        </div>
                                        <input type="email" name="email" id="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}" placeholder="Ingrese correo electrónico">

                                    </div>
                                </div>
                                <!-- Tipo -->
                                <div class="col-12 col-md-6 mb-3">
                                    <label for="tipo_id">Tipo</label> <span class="obligatorio">*</span>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-list"></i>
                                            </span>
                                        </div>
                                        <select name="tipo_id" id="tipo_id"
                                            class="form-control  @error('tipo_id') is-invalid @enderror">

                                            <option value="2">CLIENTE</option>

                                        </select>

                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-primary" id="btnguardarcliente">
                                <i class="fas fa-save mr-1"></i> Guardar
                            </button>
                        </div>


                    </div>

                </div>
            </div>
        </div>
        <!--fin Modal Registrar proveedor -->
        <!---Modal generar Venta-->
        <!-- Modal -->
        <div class="modal fade " id="modalGenerar" tabindex="-1" role="dialog" aria-labelledby="tablaModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
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
                                                class="form-control no-border"><input type="hidden" id="id_cliente">
                                        </td>
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
                            <div id="pagos"></div>
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
                    <div id="alertaInfo"
                        style="display:none; padding:15px; background-color:#d1ecf1; color:#0c5460; border:1px solid #bee5eb; border-radius:5px; margin-top:10px;">
                        ℹ️ <strong>Información:</strong> El proceso de generación ha comenzado.
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
                                <button class="btn " style="border: 1px solid black"
                                    onclick="$('#modalGenerar').modal('hide')" type="button">
                                    <i class="fas fa-times-circle"></i> Cancelar
                                </button>
                                <button class="btn btn-primary" id="btngenerarventa" type="button">
                                    <i class="fas fa-check-circle"></i> Generar Venta
                                </button>
                            </div>
                        </div>


                    </div>

                </div>

            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success mb-2 mt-2 msj">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->has('error'))
            <div class="alert alert-danger mb-2 mt-2 msj">
                {{ $errors->first('error') }}
            </div>
        @endif


        <!-- Modal  registrar Pago -->
        <div class="modal fade " id="registroPagoModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">

                <div class="modal-content">
                    <div class="modal-header bg-orange text-white">
                        <h5 class="modal-title" id="modalLabel"><i class="fas fa-money-check-alt mr-2"></i>Registrar
                            Pagos de
                            la Venta <span id="n_ventapago"></span></h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true" class="text-white">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('pagos.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="venta_id" id="venta_id">
                        <div class="modal-body">
                            <!-- Tipo de Venta -->
                            <div class="form-group">
                                <label for="tipo_venta"><i class="fas fa-tags mr-1 text-black"></i>Tipo de
                                    Venta</label>
                                <select class="form-control" name="tipo_venta" id="tipo_venta">
                                    <option value="">Seleccione tipo de venta</option>
                                    <option value="Mixto">Mixta</option>
                                    <option value="Contado">Al Contado</option>
                                    <option value="Cuenta">A Cuenta</option>
                                </select>
                            </div>
                            <!-- Método de Pago -->
                            <div class="form-group oculto metodo_pago">
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
                                    @foreach ($cuentas as $item)
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
                            <button type="button" class="btn btn-black" style="border: 1px solid black"
                                onclick="NuevoPago();">
                                <i class="fas fa-times-circle mr-1"></i>Nuevo
                            </button>
                            <button type="submit" class="btn btn-success" onclick="return GuardarPagos(event);">
                                <i class="fas fa-save mr-1"></i>Guardar
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>

        <!--final generar venta-->
        <div class="card-body">

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
                                                    style="width: 54.6406px;">Fecha</th>
                                                <th class="sorting" tabindex="0" aria-controls="order-listing"
                                                    rowspan="1" colspan="1"
                                                    aria-label="Base Price: activate to sort column ascending"
                                                    style="width: 77.5156px;">Cliente</th>
                                                <th class="sorting" tabindex="0" aria-controls="order-listing"
                                                    rowspan="1" colspan="1"
                                                    aria-label="Base Price: activate to sort column ascending"
                                                    style="width: 77.5156px;">Total</th>
                                                <th class="sorting" tabindex="0" aria-controls="order-listing"
                                                    rowspan="1" colspan="1"
                                                    aria-label="Purchased Price: activate to sort column ascending"
                                                    style="width: 117.828px;">Estado</th>

                                                <th class="sorting" tabindex="0" aria-controls="order-listing"
                                                    rowspan="1" colspan="1"
                                                    aria-label="Actions: activate to sort column ascending"
                                                    style="width: 58.75px;">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($ventas as $vent)
                                                <tr role="row" class="{{ $loop->odd ? 'odd' : 'even' }}">
                                                    <td class="sorting_1">{{ $vent->codigo }}</td>
                                                    <td>{{ $vent->fecha }}</td>
                                                    <td>{{ $vent->cliente->nombres }}</td>
                                                    <td>{{ $vent->total }}</td>
                                                    <td>{{ $vent->estado }}</td>
                                                    <td class="d-flex center gap-2">

                                                        <!-- Botón Pago -->
                                                        @if ($vent->estado != 'Pagado' && $vent->estado != 'Anulado')
                                                            <button class="btn btn-success" data-toggle="modal"
                                                                id="btnregistrarpago" data-target="#registroPagoModal"
                                                                data-id={{ $vent->id }}
                                                                data-codigo={{ $vent->codigo }}
                                                                data-total={{ $vent->total }} title="Registrar Pagos">
                                                                <i class="fas fa-credit-card"></i>
                                                            </button>
                                                            <form
                                                                action="{{ route('ventas.update', ['id' => $vent->id]) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger"
                                                                    title="Anular Venta"
                                                                    onclick="return AnularVenta(event,'{{ $vent->codigo }}')">
                                                                    <i class="fas fa-ban"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                        @if ($vent->estado == 'Pagado')
                                                            <button class="btn btn-primary"
                                                                onclick="obtenerVenta('{{ $vent->id }}')"
                                                                title="Imprimir Venta">
                                                                <i class="fas fa-print"></i>
                                                            </button>
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
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script src="{{ asset('melody/data-table.js') }}"></script>
    <script>
        let cotizacionNumero = "";
        let totalventa = 0.00;
        $("#btnregistrarpago").on("click", function() {
            let codigo = this.dataset.codigo;
            let total = this.dataset.total;
            let id = this.dataset.id;
            totalventa = total;
            $("#n_ventapago").html(codigo + "-" + "S/" + total);
            $("#venta_id").val(id);
        });

        function anular(event, cotizacion) {
            event.preventDefault();
            let confirmacion = confirm("⚠️ ¿Estás seguro de anular la cotización " + cotizacion + "?");

            if (confirmacion) {
                event.target.closest("form").submit();
            }
        }

        async function GuardarPagos(event) {
            event.preventDefault();
            let confirmacion = confirm(
                "⚠️ ¿Estás seguro de guardar los Pagos. Revisa bien los datos antes de confirmar.?");
            let total = totalventa;
            if (confirmacion) {
                let response = await calcularPagos(total);
                if (response == "ok") {
                    event.target.closest("form").submit();

                }
            }
        }

        async function calcularPagos(total) {
            let tipo = $("#tipo_venta").val();
            let divpagos = $("#cuerpopagos");
            let totalmontos = 0.00;

            if (tipo === "Contado") {
                let montos = divpagos.find("[name='monto[]']");

                montos.each(function() {
                    const valor = parseFloat($(this).val()) || 0;
                    totalmontos += valor;
                });

                if (totalmontos === 0.00) {
                    alert("💰 Ingresa un monto válido para esta venta Contado. No hay detalles de pago.");
                    return "error";
                }
                if (totalmontos < total) {
                    alert(
                        "💰 La suma de todos los pagos es menor al total de la venta,valido solo para ventas Mixtas."
                    );
                    return "error";
                }
                if (totalmontos > total) {
                    let resp = confirm(
                        "💰 La suma de todos los pagos es mayor al total de la venta, si continuas el monto sobrante se registrara como saldo a favor para el cliente y podra ser usado en su proxima cuenta."
                    );
                    return resp ? "ok" : "error";
                }
            }
            return "ok";
        }


        function cerrarYMostrar() {
            // Cierra el modal actual
            $('#modalProveedor').modal('hide');

            // Espera a que se oculte completamente y luego abre el otro
            $('#modalProveedor').on('hidden.bs.modal', function() {
                $('#modalGenerar').modal('show');

                // Le das el foco al primer input o al modal directamente
                setTimeout(() => {
                    $('#modalGenerar')
                        .css('overflow', 'auto') // Asegura scroll interno si hay mucho contenido
                        .find('button:visible:first') // Encuentra el primer botón visible
                        .focus();

                }, 500);

                // Elimina el evento para que no se ejecute varias veces
                $(this).off('hidden.bs.modal');
            });
        }
        $("#btngenerarventa").on("click", function() {
            if ($("#id_cliente").val() == "") {
                alert("💢 El cliente de esta cotización no está registrado. Haz clic sobre él para registrarlo.");
                return;
            }
            let confirmacion = confirm("¿Estas Seguro de Generar la Venta?");
            if (!confirmacion) {
                return;
            }
            const alerta = document.getElementById("alertaInfo");
            alerta.style.display = "block";
            $("#destino").val();
            $("#total").val();
            $("#subtotal").val();
            $("#envio").val();
            $("#encomienda").val();
            $("#favor").val();
            $("#pendiente").val();
            $("#facturacion").val();
            setTimeout(async () => {
                await guardarVenta();
                alerta.style.display = "none";
            }, 3000);
        });
        async function guardarVenta() {
            try {
                const response = await fetch('/ventas/crear', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Si usas Laravel
                    },
                    body: JSON.stringify({
                        cotizacion: cotizacionNumero,
                        destino: $("#destino").val(),
                        total: $("#total").val(),
                        subtotal: $("#subtotal").val(),
                        envio: $("#envio").val(),
                        encomienda: $("#encomienda").val(),
                        favor: $("#favor").val(),
                        pendiente: $("#pendiente").val(),
                        facturacion: $("#facturacion").val()
                    })

                });

                const resultado = await response.json();

                if (response.ok) {
                    alert("✅ Venta generada con éxito.");
                    location.reload();
                } else {
                    alert("❌ Error al generar la venta: " + resultado.message);
                }
            } catch (error) {
                alert("❌ Ocurrió un error en la solicitud: " + error.message);
            }
        }

        $("#btnguardarcliente").on("click", function() {
            let telefono = $("#telefono").val();
            let nombres = $("#nombres").val();
            let tipo = $("#tipo").val();
            let direccion = $("#direccion").val();
            let ruc = $("#ruc").val();
            let codigo = $("#codigopersona").val();
            let email = $("#email").val();
            let id = cotizacionNumero;
            if (telefono == "" || nombres == "" || tipo == "") {
                alert("💢 Completa los campos obligatorios.");
                return;
            }

            $.ajax({
                url: '/proveedores/guardarcliente/' + id,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    telefono: telefono,
                    nombres: nombres,
                    tipo: tipo,
                    direccion: direccion,
                    ruc: ruc,
                    codigo: codigo,
                    email: email,
                },
                success: function(response) {
                    if (response.success) {
                        alert("✅ Cliente guardado correctamente.");
                        $("#id_cliente").val(response.id);
                        $("#cliente").val(nombres);
                        $("#cliente").off("focus");
                        $("#cliente").prop("readonly", true);
                        cerrarYMostrar();
                    } else {
                        alert("⚠️ " + response.message);
                    }
                },
                error: function(xhr) {
                    const error = xhr.responseJSON?.message || "Ocurrió un error inesperado.";
                    alert("❌ " + error);
                }
            });
        });


        function NuevoPago() {
            if (confirm("¿Estás seguro? Se eliminarán todos los datos de pago ingresados.")) {
                // Habilitar tipo de venta nuevamente
                $("#tipo_venta").prop('readonly', false);

                // Ocultar y limpiar campos relacionados
                $(".metodo_pago, .operacion, .banco, .monto, .detallespagos").addClass("oculto");
                $("#metodo_pago, #numero_operacion, #banco, #monto").val("");
                $("#cuerpopagos").html("");

            }
        }

        $("#tipo_venta").on("change", function() {
            const valor = $(this).val();
            if (valor === "Mixto" || valor === "Contado") {
                $(".metodo_pago").removeClass("oculto");
                $(this).prop('readonly', true);

            } else {
                if (valor === "Cuenta") {
                    $(".metodo_pago").addClass("oculto");

                    $(this).prop('readonly', true);

                }


            }


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
                    if (["Mixto", "Contado"].includes($("#tipo_venta").val())) {

                        $(".detallespagos").removeClass("oculto");

                    } else {
                        $(".detallespagos").addClass("oculto");

                    }

                } else {
                    $(".operacion").addClass("oculto");
                    $(".monto").addClass("oculto");
                    $(".banco").addClass("oculto");
                    $(".detallespagos").addClass("oculto");

                }
            }
        });
        $("#btnagregarPago").on("click", function() {
            const tipo_venta = $("#tipo_venta")?.val();
            const metodo_pago = $("#metodo_pago")?.val();
            const banco = $("#banco")?.val();
            const operacion = $("#numero_operacion")?.val();
            const monto = $("#monto")?.val();
            if (tipo_venta == "") {
                alert("💢 Seleccione el Tipo de Venta");
                return;
            }

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

        document.getElementById('buscarInput').addEventListener('keyup', function() {
            const valor = this.value.toLowerCase();
            const filas = document.querySelectorAll('#tablaContenido tr');

            filas.forEach(fila => {
                const texto = fila.textContent.toLowerCase();
                fila.style.display = texto.includes(valor) ? '' : 'none';
            });
        });

        function obtenerCotizacion(id) {
            $('#modalGenerar').appendTo('body').modal({
                backdrop: true,
                keyboard: false
            });
            cotizacionNumero = id;
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
                    $("#id_cliente").val(respuesta.persona_id);
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
                        <td class="text-center">${precio.toFixed(2)}</td>
                        <td class="text-center">${(precio * cantidad).toFixed(2)}</td>
                    </tr>
                `);


                    });


                },
                error: function(xhr, status, error) {
                    console.error('Error en la solicitud:', error);
                }
            });
        }


        function obtenerVenta(id) {
            $("#btngenerarventa").html('<i class="fas fa-print"></i> Imprimir');
            $("#btngenerarventa").off("click");
            $("#btngenerarventa").on("click", function(e) {
                e.preventDefault();


            });

            $('#modalGenerar').appendTo('body').modal({
                backdrop: true,
                keyboard: false
            });
            $.ajax({
                url: 'ventas/' + id,
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
                    $("#codigo").html(''),
                        console.log(respuesta.data);
                    let total_utilidades = 0;
                    // Llenar datos principales
                    $("#cliente").val(respuesta.data.cliente.nombres);
                    $("#id_cliente").val(respuesta.data.persona_id);
                    $("#destino").val(respuesta.data.destino);
                    $("#total").val(Number(respuesta.data.total).toFixed(2));
                    $("#subtotal").val(Number(respuesta.data.subtotal).toFixed(2));
                    $("#envio").val(Number(respuesta.data.envio).toFixed(2));
                    $("#encomienda").val(Number(respuesta.data.gasto_envio).toFixed(2));
                    $("#favor").val(Number(respuesta.data.saldo_a_favor).toFixed(2));
                    $("#pendiente").val(Number(respuesta.data.saldo_pendiente).toFixed(2));
                    $("#facturacion").val(Number(respuesta.data.comision_facturacion).toFixed(2));
                    $("#utilidad").html(Number(respuesta.data.utilidad).toFixed(2));
                    $("#codigo").html(respuesta.data.codigo);

                    // Iterar productos
                    respuesta.data.detalles.forEach(element => {
                        const cantidad = parseInt(element.cantidad);
                        const producto = element.producto.modelo + " " +
                            element.producto.marca + " " +
                            element.producto.capacidad + " " +
                            (element.registrado == 1 ? "REGISTRADO" : "LIBRE");


                        const precio_compra = parseFloat(element.precio_unitario ??
                            0); // si no hay, será 0

                        $("#detalles").append(`
                    <tr>
                        <td class="text-center">${cantidad}</td>
                        <td class="text-center">${producto}</td>
                        <td class="text-center">${precio_compra.toFixed(2)}</td>
                        <td class="text-center">${(precio_compra * cantidad).toFixed(2)}</td>
                    </tr>
                `);
                        const abonos = respuesta.data.abonos; // Array de objetos de abono

                        // Construye las filas dinámicamente
                        const filas = abonos.map(abono => `
  <tr>
    <td>${abono.fecha}</td>
    <td>${abono.metodo_pago} – ${abono.operacion?.cuenta?.titular || 'NO APLICA'}</td>
    <td>${abono.operacion.numero==0? "NO APLICA":abono.operacion.numero || ''}</td>
    <td class="text-right">${Number(abono.monto).toFixed(2)}</td>
  </tr>
`).join("");

                        const totalAbonos = abonos
                            .reduce((sum, abono) => sum + Number(abono.monto), 0);
                        const totalPendiente = Number(respuesta.data.saldo_pendiente);

                        // 3) Montamos el tfoot
                        const pie = `
  <tfoot>
    <tr>
      <td colspan="3"  style="border:1px solid white;" class="text-right">Total Abonos</td>
      <td class="text-right bg-orange">${totalAbonos.toFixed(2)}</td>
    </tr>
    <tr>
      <td colspan="3" style="border:1px solid white;" class="text-right">Total Pendiente</td>
      <td class="text-right bg-orange">${totalPendiente.toFixed(2)}</td>
    </tr>
  </tfoot>
`;

                        const tablaPagos = `
  
  <table class="table table-sm table-bordered ">
    <thead>
      <tr>
        <td class="bg-orange text-white">Fecha</td>
        <td class="bg-orange text-white">Método Pago – Titular Cuenta</td>
        <td class="bg-orange text-white">Nro Operación</td>
        <td class="text-right bg-orange text-white">Monto</td>
      </tr>
    </thead>
    <tbody>
      ${filas}
    </tbody>
      ${pie}
  </table>
`;

                        // Inserción en el div de pagos
                        $("#cuerpopagos")
                            .empty()
                            .append(tablaPagos);


                        // Vacías el contenedor y le pones la tabla
                        $("#pagos").empty().append(tablaPagos);


                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error en la solicitud:', error);
                }
            });
        }

        function prepararParaCaptura() {
            document
                .getElementById("modalGenerar")
                .querySelectorAll("input[readonly],input, textarea, input:not([type])")
                .forEach(el => {
                    // 1) Creamos el <p> y almacenamos el HTML original
                    const p = document.createElement("p");
                    p.dataset.original = el.outerHTML;

                    // 2) Copiamos el valor y estilos
                    p.textContent = el.value ?? el.textContent;
                    const computed = getComputedStyle(el);
                    [
                        "font", "color", "backgroundColor", "padding", "margin", "border",
                        "borderRadius", "display", "width", "height", "textAlign", "whiteSpace"
                    ].forEach(prop => {
                        p.style[prop] = computed[prop];
                    });
                    p.style.whiteSpace = "pre-wrap";

                    // 3) Reemplazamos en el DOM
                    el.parentNode.replaceChild(p, el);
                });
        }



        if ($(".msj").length) {
            setTimeout(() => {
                $(".msj").fadeOut();

            }, 3000);
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
        if ($("#id_cliente").val() == "") {
            $("#cliente").on("focus", function() {
                $('#modalProveedor').appendTo('body').modal({
                    backdrop: false,
                    keyboard: false
                });

                $("#nombres").val($("#cliente").val());
            });
        } else {
            $("#cliente").prop("readonly", true);
        }

        $("#nombres").on("keyup", function() {
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
