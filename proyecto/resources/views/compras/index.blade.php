@extends('partials.layout')
@section('estilos')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
@endsection
@section('pagina')
    <div class="content-wrapper" id="pagina_div">
        @component('componentes.com_titulo', [
            'titulo' => 'Gestión de Compras',
            'paginaprincipal' => 'Compras',
            'paginaactual' => 'Compras',
        ])
        @endcomponent

        <div class="card">
            <div class="d-flex  align-items-center gap-3 p-2">
                <!-- Columna de la tarjeta -->
                <div class="col-12 col-md-8 grid-margin ">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-0">Cantidad de Compras</h4>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-inline-block pt-3">
                                    <div class="d-md-flex">
                                        <h2 class="mb-0">{{ $compras->count() }}</h2>
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

                <!-- Columna del botón -->
                <div class="col-12 col-md-4 text-md-center text-center  mt-3 mt-md-0">
                    <button class="btn btn-primary rounded" data-toggle="modal" data-target="#modalCompra">
                        <i class="fas fa-plus-circle mr-1"></i>
                        Nueva Compra
                    </button>
                </div>


                <!---Inicio Modal registrar recurso--->
                <div class="modal fade" id="modalCompra" tabindex="-1" role="dialog" aria-labelledby="modalCompraLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">Registrar Compra</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                    <span aria-hidden="true">X</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                @if (session('success'))
                                    <div class="alert alert-success msj">{{ session('success') }}</div>
                                @endif
                                @if ($errors->has('general'))
                                    <div class="alert alert-danger msj">{{ $errors->first('general') }}</div>
                                @endif

                                <form id="formCompra" method="POST" action="{{ route('compras.store') }}">
                                    @csrf

                                    <div class="row">
                                        <!-- Número de Compra -->
                                        <div class="col-md-6 mb-3">
                                            <label for="numero">N° Compra <span class="obligatorio">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                                </div>
                                                <input type="text" name="numero" id="numero"
                                                    class="form-control @error('numero') is-invalid @enderror"
                                                    value="{{ old('numero', $codigo) }}" placeholder="Ej: CMP-0001"
                                                    readonly>
                                            </div>
                                            @error('numero')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Persona (Proveedor) -->
                                        <div class="col-md-6 mb-3">
                                            <label for="persona_id">Proveedor <span class="obligatorio">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                                </div>
                                                <select name="persona_id" id="persona_id"
                                                    class="form-control @error('persona_id') is-invalid @enderror">
                                                    <option value="">Seleccione proveedor</option>
                                                    @foreach ($proveedores as $persona)
                                                        <option value="{{ $persona->id }}"
                                                            {{ old('persona_id') == $persona->id ? 'selected' : '' }}>
                                                            {{ $persona->nombres }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('persona_id')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Tipo de Compra -->
                                        <div class="col-md-6 mb-3">
                                            <label for="tipo_compra">Tipo de Compra <span
                                                    class="obligatorio">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-shopping-cart"></i></span>
                                                </div>
                                                <select name="tipo_compra" id="tipo_compra"
                                                    class="form-control @error('tipo_compra') is-invalid @enderror">
                                                    @foreach (App\Enums\EnumTipoCompra::cases() as $tipo)
                                                        <option value="{{ $tipo->value }}"
                                                            {{ old('tipo_compra') == $tipo->value ? 'selected' : '' }}>
                                                            {{ ucfirst($tipo->value) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('tipo_compra')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Fecha -->
                                        <div class="col-md-6 mb-3">
                                            <label for="fecha_compra">Fecha <span class="obligatorio">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-calendar-alt"></i></span>
                                                </div>
                                                <input type="date" name="fecha_compra" id="fecha_compra"
                                                    class="form-control @error('fecha_compra') is-invalid @enderror"
                                                    value="{{ old('fecha_compra', now()->format('Y-m-d')) }}">
                                            </div>
                                            @error('fecha_compra')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Tipo Documento -->
                                        <div class="col-md-6 mb-3">
                                            <label for="tipo_documento">Tipo de Documento <span
                                                    class="obligatorio">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                                </div>
                                                <select name="tipo_documento" id="tipo_documento"
                                                    class="form-control @error('tipo_documento') is-invalid @enderror">
                                                    @foreach (App\Enums\EnumTipoDocumento::cases() as $tipo)
                                                        <option value="{{ $tipo->value }}"
                                                            {{ old('tipo_documento') == $tipo->value ? 'selected' : '' }}>
                                                            {{ ucfirst($tipo->value) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('tipo_documento')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Número Documento -->
                                        <div class="col-md-6 mb-3">
                                            <label for="numero_documento">N° Documento <span
                                                    class="obligatorio"></span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                </div>
                                                <input type="text" name="numero_documento" id="numero_documento"
                                                    class="form-control @error('numero_documento') is-invalid @enderror"
                                                    value="{{ old('numero_documento') }}">
                                            </div>
                                            @error('numero_documento')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Total -->
                                        <div class="col-md-6 mb-3">
                                            <label for="total">Total (S/.) <span class="obligatorio"></span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-money-bill-wave"></i></span>
                                                </div>
                                                <input type="number" step="0.01" name="total" id="total"
                                                    class="form-control @error('total') is-invalid @enderror"
                                                    value="{{ old('total') }}" placeholder="Ingrese total">
                                            </div>
                                            @error('total')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Estado -->
                                        <div class="col-md-6 mb-3">
                                            <label for="estado">Estado <span class="obligatorio">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-flag"></i></span>
                                                </div>
                                                <select name="estado" id="estado"
                                                    class="form-control @error('estado') is-invalid @enderror">
                                                    @foreach (App\Enums\EnumEstadoCompra::cases() as $estado)
                                                        <option value="{{ $estado->value }}"
                                                            {{ old('estado') == $estado->value ? 'selected' : '' }}>
                                                            {{ ucfirst($estado->value) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('estado')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <br>

                                        <div class="modal-body">
                                            <!-- Método de Pago -->
                                            <div class="form-group  metodo_pago">
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
                                                    @foreach ($cuentas as $item)
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


                                        <div class="col-md-12 mt-4 mb-4 ">
                                            <h5>Productos de la Compra</h5>
                                        </div>

                                        <br>
                                        <!--DETALLES DE LAS VENTAS --->

                                        <div class="col-md-6 mb-3">
                                            <label for="producto_id" class="form-label">Producto</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-box"></i></span>
                                                </div>
                                                <select name="producto_id" id="producto_id" class="form-select">
                                                    <option value="">Seleccione un producto</option>
                                                    @foreach ($productos as $producto)
                                                        <option value="{{ $producto->id }}"
                                                            data-tipo='{{ $producto->tipo }}'
                                                            data-sim='{{ $producto->sim }}'>
                                                            {{ $producto->marca }} {{ $producto->modelo }}
                                                            {{ $producto->capacidad }}
                                                            @if (strtolower($producto->sim) === 'si')
                                                                SIM
                                                            @endif
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>






                                        {{-- IMEI --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="imei" class="form-label">IMEI</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">

                                                    <span class="input-group-text"><i
                                                            class="fas fa-mobile-alt"></i></span>
                                                </div>
                                                <input type="text" name="imei" id="imei" class="form-control"
                                                    placeholder="IMEI del producto" value="" readonly>
                                            </div>
                                        </div>

                                        {{-- Color --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="color" class="form-label">Color</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">

                                                    <span class="input-group-text"><i class="fas fa-palette"></i></span>
                                                </div>
                                                <input type="text" name="color" id="color" class="form-control"
                                                    placeholder="Color del producto" value="-">
                                            </div>
                                        </div>

                                        {{-- Precio --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="precio" class="form-label">Precio</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">

                                                    <span class="input-group-text"><i
                                                            class="fas fa-dollar-sign"></i></span>
                                                </div>
                                                <input type="number" step="0.01" id="precio" name="precio"
                                                    class="form-control" placeholder="Precio" value="0.00">
                                            </div>
                                        </div>

                                        {{-- Cantidad --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="cantidad" class="form-label">Cantidad</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">

                                                    <span class="input-group-text"><i
                                                            class="fas fa-sort-numeric-up"></i></span>
                                                </div>
                                                <input type="number" name="cantidad" id="cantidad"
                                                    class="form-control" placeholder="Cantidad" value="1">
                                            </div>
                                        </div>

                                        {{-- Registrado (checkbox con diseño bonito) --}}
                                        <div class="col-md-6 mb-3 ">
                                            <label class="form-label">Registrado</label>
                                            <div class="border rounded px-5 py-2 d-flex gap-2 align-items-center">
                                                <input class="form-check-input me-2" type="checkbox" name="registrado"
                                                    id="registrado" value="1">

                                                <label class="form-check-label" for="registrado">
                                                    <i class="fas fa-signal me-1"></i> Marcar como registrado
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-12 ">

                                            <div class="d-flex gap-2">
                                                <button type="button" type="button" id="btnagregar"
                                                    onclick="agregarProducto()"
                                                    class="btn  d-flex align-items-center gap-2 btn-border">
                                                    <i class="fas fa-list"></i>
                                                    Agregar
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>

                                        </div>
                                        <div class="p-2 col-md-12">
                                            <ul id="detalles_productos" class="col-md-12">

                                            </ul>
                                        </div>
                                        <!--FIN DE LOS DETALLES DE VENTAS --->

                                    </div>

                                    <div class="modal-footer mt-4">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                            <i class="fas fa-times-circle mr-1"></i> Cancelar
                                        </button>
                                        <button type="submit" class="btn btn-primary" onclick="guardar(event)">
                                            <i class="fas fa-save mr-1"></i> Guardar
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

                <!---Fin Modal registrar recurso--->

                <!-- Inicio Modal editar producto -->
                <div class="modal fade" id="modalCompraeditar" tabindex="-1" role="dialog"
                    aria-labelledby="modalCompraLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">Modificar Compra</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                    <span aria-hidden="true">X</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                @if ($errors->has('general_edit'))
                                    <div class="alert alert-danger msj">{{ $errors->first('general_edit') }}</div>
                                @endif

                                <form id="formCompraeditar" method="POST"
                                    action="{{ route('compras.update', ['id' => '0']) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <!-- Número de Compra -->
                                        <div class="col-md-6 mb-3">
                                            <label for="numero">N° Compra <span class="obligatorio">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                                </div>
                                                <input type="text" name="numero" id="numero_ed"
                                                    class="form-control @error('numero') is-invalid @enderror"
                                                    value="{{ old('numero', $codigo) }}" placeholder="Ej: CMP-0001"
                                                    readonly>
                                            </div>
                                            @error('numero')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Persona (Proveedor) -->
                                        <div class="col-md-6 mb-3">
                                            <label for="persona_id">Proveedor <span class="obligatorio">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                                </div>
                                                <select name="persona_id" id="persona_id_ed"
                                                    class="form-control @error('persona_id') is-invalid @enderror">
                                                    <option value="">Seleccione proveedor</option>
                                                    @foreach ($proveedores as $persona)
                                                        <option value="{{ $persona->id }}"
                                                            {{ old('persona_id') == $persona->id ? 'selected' : '' }}>
                                                            {{ $persona->nombres }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('persona_id')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Tipo de Compra -->
                                        <div class="col-md-6 mb-3">
                                            <label for="tipo_compra">Tipo de Compra <span
                                                    class="obligatorio">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-shopping-cart"></i></span>
                                                </div>
                                                <select name="tipo_compra" id="tipo_compra_ed"
                                                    class="form-control @error('tipo_compra') is-invalid @enderror">
                                                    @foreach (App\Enums\EnumTipoCompra::cases() as $tipo)
                                                        <option value="{{ $tipo->value }}"
                                                            {{ old('tipo_compra') == $tipo->value ? 'selected' : '' }}>
                                                            {{ ucfirst($tipo->value) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('tipo_compra')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Fecha -->
                                        <div class="col-md-6 mb-3">
                                            <label for="fecha_compra">Fecha <span class="obligatorio">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-calendar-alt"></i></span>
                                                </div>
                                                <input type="date" name="fecha_compra" id="fecha_compra_ed"
                                                    class="form-control @error('fecha_compra') is-invalid @enderror"
                                                    value="{{ old('fecha_compra', now()->format('Y-m-d')) }}">
                                            </div>
                                            @error('fecha_compra')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Tipo Documento -->
                                        <div class="col-md-6 mb-3">
                                            <label for="tipo_documento">Tipo de Documento <span
                                                    class="obligatorio">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                                </div>
                                                <select name="tipo_documento" id="tipo_documento_ed"
                                                    class="form-control @error('tipo_documento') is-invalid @enderror">
                                                    @foreach (App\Enums\EnumTipoDocumento::cases() as $tipo)
                                                        <option value="{{ $tipo->value }}"
                                                            {{ old('tipo_documento') == $tipo->value ? 'selected' : '' }}>
                                                            {{ ucfirst($tipo->value) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('tipo_documento')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Número Documento -->
                                        <div class="col-md-6 mb-3">
                                            <label for="numero_documento">N° Documento <span
                                                    class="obligatorio"></span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                </div>
                                                <input type="text" name="numero_documento" id="numero_documento_ed"
                                                    class="form-control @error('numero_documento') is-invalid @enderror"
                                                    value="{{ old('numero_documento') }}" placeholder="Ej: F001-000123">
                                            </div>
                                            @error('numero_documento')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Total -->
                                        <div class="col-md-6 mb-3">
                                            <label for="total">Total (S/.) <span class="obligatorio"></span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-money-bill-wave"></i></span>
                                                </div>
                                                <input type="number" step="0.01" name="total" id="total_ed"
                                                    class="form-control @error('total') is-invalid @enderror"
                                                    value="{{ old('total') }}" placeholder="Ingrese total">
                                            </div>
                                            @error('total')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Estado -->
                                        <div class="col-md-6 mb-3">
                                            <label for="estado">Estado <span class="obligatorio">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-flag"></i></span>
                                                </div>
                                                <select name="estado" id="estado_ed"
                                                    class="form-control @error('estado') is-invalid @enderror">
                                                    @foreach (App\Enums\EnumEstadoCompra::cases() as $estado)
                                                        <option value="{{ $estado->value }}"
                                                            {{ old('estado') == $estado->value ? 'selected' : '' }}>
                                                            {{ ucfirst($estado->value) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('estado')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <br>

                                    </div>

                                    <div class="modal-footer mt-4">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                            <i class="fas fa-times-circle mr-1"></i> Cancelar
                                        </button>
                                        <button type="submit" class="btn btn-primary" onclick="editar(event)">
                                            <i class="fas fa-save mr-1"></i> Modificar
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>


                <!--Fin Modal editar producto -->

            </div>
            <div class="col-12 text-center mt-3">
                <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">
                    <input type="search" id="input_imei" class="form-control w-auto" placeholder="Ingrese el imei...">
                    <button class="btn btn-primary rounded" id="buscar">
                        <i class="fas fa-plus-circle mr-1"></i>
                        Buscar
                    </button>
                </div>
            </div>
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
                    <div class="alert alert-success  mb-4 msj">{{ session('success_edit') }}</div>
                @endif
                {{ $compras->links() }}
                <br>
                <hr>
                <h4 class="card-title">Lista de Compras</h4>
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
                                                        style="width: 54.6406px;">N° Documento</th>
                                                    <th class="sorting" tabindex="0" aria-controls="order-listing"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Ship to: activate to sort column ascending"
                                                        style="width: 54.6406px;">Proveedor</th>
                                                    <th class="sorting" tabindex="0" aria-controls="order-listing"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Base Price: activate to sort column ascending"
                                                        style="width: 77.5156px;">Fecha Compra</th>
                                                    <th class="sorting" tabindex="0" aria-controls="order-listing"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Purchased Price: activate to sort column ascending"
                                                        style="width: 117.828px;">Total</th>

                                                    <th class="sorting" tabindex="0" aria-controls="order-listing"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Actions: activate to sort column ascending"
                                                        style="width: 58.75px;">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($compras as $item)
                                                    <tr role="row" class="{{ $loop->odd ? 'odd' : 'even' }}">
                                                        <td class="sorting_1">{{ $item->numero }}</td>
                                                        <td>{{ $item->numero_documento }}</td>
                                                        <td>{{ $item->persona->nombres }}</td>

                                                        <td>{{ $item->fecha_compra }}</td>
                                                        <td>{{ $item->total }}</td>
                                                        <td class="d-flex gap-2 align-items-center">
                                                            <form
                                                                action="{{ route('compras.destroy', ['id' => $item->id]) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')

                                                                <button type="submit"
                                                                    class="btn {{ $item->estado === 'anulado' ? 'btn-danger' : 'btn-outline-primary' }}"
                                                                    {{ $item->estado === 'anulado' ? 'disabled' : '' }}
                                                                    title="Anular Compra"
                                                                    onclick="return eliminar(event, '{{ $item->numero }}')">
                                                                    <i class="fas fa-ban"></i> Anular
                                                                </button>
                                                            </form>


                                                            {{-- -  <button class="btn btn-outline-primary"
                                                                {{ $item->estado == 'anulado' ? 'disabled' : '' }}
                                                                onclick="llenarParaEditar(this,event)"
                                                                data-id="{{ $item->id }}"
                                                                data-persona="{{ $item->persona_id }}"
                                                                data-tipo_compra="{{ $item->tipo_compra }}"
                                                                data-numero="{{ $item->numero }}"
                                                                data-numero_documento="{{ $item->numero_documento }}"
                                                                data-tipo_documento="{{ $item->tipo_documento }}"
                                                                data-fecha_compra="{{ $item->fecha_compra ?? '' }}"
                                                                data-total="{{ $item->total ?? '' }}"
                                                                data-estado="{{ $item->estado ?? '' }}">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            --}}
                                                            <button class="btn btn-outline-primary" type="button"
                                                                onclick="detalles({{ $item->id }})">
                                                                <i class="fas fa-eye"></i>
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

    </div>
    <div id="modalResultado" class="modal"
        style="
    display:none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
">
        <div class="modal-content"
            style="
        background-color: #fff;
        margin: 5% auto;
        padding: 20px 30px;
        border-radius: 12px;
        width: 90%;
        max-width: 450px;
        max-height: 80vh;              /* 🔹 límite de altura */
        overflow-y: auto;              /* 🔹 scroll vertical interno */
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        animation: aparecer 0.3s ease-out;
    ">
            <span id="cerrarModal" class="cerrar"
                style="
            color: #888;
            float: right;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.2s;
        ">&times;</span>

            <h5
                style="
            text-align: center;
            color: #333;
            font-size: 20px;
            margin-bottom: 15px;
            border-bottom: 2px solid #007BFF;
            padding-bottom: 8px;
        ">
                Resultado de búsqueda</h5>

            <div id="resultado"
                style="
            font-size: 15px;
            color: #333;
            line-height: 1.6;
            overflow-wrap: break-word;
        ">
                <!-- Aquí se mostrará el resultado -->
            </div>
        </div>

        <style>
            @keyframes aparecer {
                from {
                    transform: translateY(-30px);
                    opacity: 0;
                }

                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            .cerrar:hover {
                color: #007BFF !important;
            }

            /* 🔹 Scroll bonito */
            .modal-content::-webkit-scrollbar {
                width: 8px;
            }

            .modal-content::-webkit-scrollbar-thumb {
                background: #007BFF;
                border-radius: 10px;
            }

            .modal-content::-webkit-scrollbar-thumb:hover {
                background: #0056b3;
            }
        </style>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    @if (session('show_modal') || session('success'))
        <script>
            $(document).ready(function() {
                $('#modalCompra').modal('show');

            });
        </script>
    @endif
    @if (session('show_modal_edit'))
        <script>
            $(document).ready(function() {
                $('#modalCompraeditar').modal('show');
            });
        </script>
    @endif

    <script>
        document.getElementById('buscar').addEventListener('click', async () => {
            const imei = document.getElementById('input_imei').value.trim();
            const resultadoDiv = document.getElementById('resultado');
            const modal = document.getElementById('modalResultado');

            if (!imei) {
                alert('Por favor, ingrese un IMEI.');
                return;
            }

            resultadoDiv.innerHTML = 'Buscando...';
            modal.style.display = 'flex';

            try {
                const response = await fetch(`/compras/buscar/${encodeURIComponent(imei)}`);
                if (!response.ok) throw new Error('Error al obtener los datos');

                const data = await response.json();

                // Aquí puedes personalizar cómo se muestra el resultado
                if (data && data.data) {
                    const detalle = data.data;
                    const compra = detalle.compra;
                    const persona = compra ? compra.persona : null;

                    resultadoDiv.innerHTML = `
        <h6>📱 Detalle del Producto</h6>
        <p><strong>IMEI:</strong> ${detalle.imei}</p>
        <p><strong>Color:</strong> ${detalle.color}</p>
        <p><strong>Precio:</strong> S/ ${detalle.precio}</p>
        <p><strong>Cantidad:</strong> ${detalle.cantidad}</p>
        <hr>
        <h6>🧾 Detalle de la Compra</h6>
        <p><strong>N° Compra:</strong> ${compra.numero}</p>
        <p><strong>Tipo:</strong> ${compra.tipo_compra}</p>
        <p><strong>Fecha:</strong> ${compra.fecha_compra}</p>
        <p><strong>Documento:</strong> ${compra.tipo_documento} - ${compra.numero_documento}</p>
        <p><strong>Estado:</strong> ${compra.estado}</p>
        <hr>
        <h6>👤 Proveedor</h6>
        <p><strong>Nombre:</strong> ${persona ? persona.nombres : 'Sin datos'}</p>
        <p><strong>Teléfono:</strong> ${persona ? persona.telefono : '-'}</p>
        <p><strong>Dirección:</strong> ${persona ? persona.direccion : '-'}</p>
    `;
                } else {
                    resultadoDiv.innerHTML = `<p>No se encontraron resultados para este IMEI.</p>`;
                }
            } catch (error) {
                resultadoDiv.innerHTML = '<p style="color:red;">Ocurrió un error en la búsqueda.</p>';
                console.error(error);
            }
        });

        // Cerrar modal al hacer clic en la “x” o fuera del contenido
        document.getElementById('cerrarModal').addEventListener('click', () => {
            document.getElementById('modalResultado').style.display = 'none';
        });
        window.addEventListener('click', (e) => {
            const modal = document.getElementById('modalResultado');
            if (e.target === modal) modal.style.display = 'none';
        });


        $("#tipo_compra").on("change", function() {
            const valor = $(this).val();
            console.log(valor);
            if (valor === "mixto" || valor === "contado") {
                $(".metodo_pago").removeClass("oculto");
                $(this).prop('readonly', true);

            } else {
                if (valor === "credito") {
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
                    if (["mixto", "contado"].includes($("#tipo_compra").val())) {

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


        $(document).on('click', '#btnagregarPago', function() {
            let totalCompra = $("#total").val();

            if (totalCompra == "" || totalCompra <= 0) {
                alert("✖️ Ingresa el Total de la Compra.");
                return;
            }
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
            <input type="hidden" name="operac[]" value="${operacion}">
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

        function detalles(id) {
            $.ajax({
                url: '/compras/' + id,
                method: 'GET',
                dataType: 'html',
                success: function(response) {
                    $('#pagina_div').html(response);
                    inicializarRecalculoPrecios();

                },
                error: function(xhr, status, error) {
                    $('#pagina_div').html('<p>Error al cargar el contenido.</p>');
                    console.log(error);
                }
            });

        }

        const select = new TomSelect("#producto_id", {
            placeholder: "Buscar producto",
            allowEmptyOption: true
        });
        document.getElementById('imei').addEventListener('input', function() {
            const imei = this.value.trim();

            if (imei.length === 15) {
                $("#btnagregar").click();
            }
        });
        $("#producto_id").on("change", function() {
            const tipo = $('#producto_id option:selected').data('tipo')?.toString().toUpperCase();
            const sim = $('#producto_id option:selected').data('sim')?.toString().toLowerCase();

            // Por defecto
            $("#cantidad").prop("readonly", false);
            $("#imei").val("-");
            $("#imei").prop("readonly", true);

            if (tipo === "OTRO") {
                // Ya está cubierto por los valores por defecto
            } else if (tipo === "TABLET") {
                if (sim === "si") {
                    $("#cantidad").prop("readonly", true);
                    $("#imei").prop("readonly", false);
                    $("#imei").val("");
                    $("#imei").focus();

                }
            } else if (tipo === "CELULAR") {
                $("#imei").val("");

                $("#cantidad").prop("readonly", true);
                $("#imei").prop("readonly", false);
                $("#imei").focus();

            }
        });



        $('#imei').on('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });
        var detallesProductos = [];
        let total_compra = 0;

        function agregarProducto() {
            const ulresultados = $('#detalles_productos');
            const producto_text = $('#producto_id option:selected').text();
            const producto_id = $('#producto_id').val();
            const imei = $('#imei').val();
            const color = $('#color').val();
            let precio = $('#precio').val();
            let cantidad = $('#cantidad').val();
            const registrado = $('#registrado').prop("checked") ? "SI" : "NO";

            if (!producto_id) return alert('Seleccione un producto.');
            if (!imei) return alert('Ingrese el IMEI.');
            if (!color) return alert('Ingrese el color.');
            if (!precio) return alert('Ingrese el precio.');
            if (!cantidad) return alert('Ingrese la cantidad.');

            // Agregar al array
            detallesProductos.push({
                producto_id,
                producto_text,
                imei,
                color,
                precio,
                cantidad,
                registrado
            });
            precio = parseFloat(precio);
            cantidad = parseInt(cantidad);

            total_compra += (precio * cantidad);

            actualizarListaVisual();
            $("#imei").val("");
            $("#total").val(total_compra);

            $("#imei").focus();
        }

        function eliminarItem(imeiBuscado) {
            const itemEncontrado = detallesProductos.find(item => item.imei === imeiBuscado);

            if (itemEncontrado) {
                const subtotal = parseFloat(itemEncontrado.precio) * parseInt(itemEncontrado.cantidad);
                total_compra -= subtotal;

                // Evita que se asigne NaN
                if (isNaN(total_compra)) total_compra = 0;

                $("#total").val(total_compra.toFixed(2));

                // Eliminar el item
                detallesProductos = detallesProductos.filter(item => item.imei !== imeiBuscado);

                actualizarListaVisual();
            }
        }


        function actualizarListaVisual() {
            $('#detalles_productos').empty();
            detallesProductos.forEach(item => {
                const li = `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>${item.producto_text}</strong><br>
                    IMEI: ${item.imei} | Color: ${item.color} | Precio: S/ ${item.precio} | Cantidad: ${item.cantidad} | Registrado: ${item.registrado}
                </div>
                <button type="button" class="btn btn-sm btn-danger ml-2" onclick="eliminarItem('${item.imei}')">
                    <i class="fas fa-trash"></i>
                </button>
                <input type="hidden" name="productos[${item.imei}][producto_id]" value="${item.producto_id}">
                <input type="hidden" name="productos[${item.imei}][imei]" value="${item.imei}">
                <input type="hidden" name="productos[${item.imei}][color]" value="${item.color}">
                <input type="hidden" name="productos[${item.imei}][precio]" value="${item.precio}">
                <input type="hidden" name="productos[${item.imei}][cantidad]" value="${item.cantidad}">
                <input type="hidden" name="productos[${item.imei}][registrado]" value="${item.registrado=="SI"?"1":"0"}">
            </li>
        `;
                $('#detalles_productos').append(li);
            });
        }
    </script>




    <script>
        function editar(event) {
            event.preventDefault();
            const estado = $('#estado_ed').val();

            // Confirmación general
            if (confirm('¿Estás seguro de que deseas modificar esta compra?')) {

                // Si el estado es "anulado", advertir sobre el efecto en el almacén
                if (estado === "anulado") {
                    const confirmarAnulacion = confirm(
                        'Atención:\n\nAl anular esta compra, todos los productos asociados serán eliminados del almacén .\n\n¿Deseas continuar?'
                    );

                    if (!confirmarAnulacion) {
                        return false; // Canceló la anulación
                    }
                }

                // Enviar el formulario
                event.target.closest('form').submit();
            }

            return false;
        }


        function eliminar(event, nombre) {
            event.preventDefault();

            if (confirm(`¿Estás seguro de que deseas Anular a ${nombre}?`)) {

                event.target.closest('form').submit();
            }
            event.target.blur();
            event.target.classList.remove('active');
            return false;

        }
        async function calcularPagos(total) {
            let tipo = $("#tipo_compra").val();
            let divpagos = $("#cuerpopagos");
            let totalmontos = 0.00;

            if (tipo === "contado") {
                let montos = divpagos.find("[name='monto[]']");

                montos.each(function() {
                    const valor = parseFloat($(this).val()) || 0;
                    totalmontos += valor;
                });

                if (totalmontos === 0.00) {
                    alert("💰 Ingresa un monto válido para esta Compra Contado. No hay detalles de pago.");
                    return "error";
                }
                if (totalmontos < total) {
                    alert(
                        "💰 La suma de todos los pagos es menor al total de la Compra,valido solo para Compras Mixtas."
                    );
                    return "error";
                }
                if (totalmontos > total) {
                    alert(
                        "💰 La suma de todos los pagos es mayor al total de la Compra."
                    );
                    return "error";
                }

                $("#estado").val("pagado");
            }
            return "ok";
        }

        async function guardar(event) {

            event.preventDefault();
            let totalCompra = $("#total").val();
            if (confirm(
                    `¿Estás seguro de que deseas guardar esta Compra despues de haber revisado todos los detalles.?`
                )) {
                if (await calcularPagos(totalCompra) == "ok") {
                    event.target.closest('form').submit();

                }
            }
            return false;
        }

        function inicializarRecalculoPrecios() {
            const tabla = document.querySelector('form table');
            if (!tabla) return;

            const totalCell = document.getElementById("totalFinal");

            const parseNumero = (valor) => parseFloat((valor || "0").replace(/[^\d.]/g, "")) || 0;

            const recalcular = () => {
                let total = 0;
                tabla.querySelectorAll("tbody tr").forEach(row => {
                    const cantidad = parseNumero(row.querySelector(".cantidad")?.textContent);
                    const precioInput = row.querySelector(".precio");
                    const precio = parseNumero(precioInput?.value);
                    const subtotal = cantidad * precio;
                    row.querySelector(".subtotal").textContent = subtotal.toFixed(2);
                    total += subtotal;
                });
                totalCell.textContent = `S/ ${total.toFixed(2)}`;
            };

            tabla.addEventListener("input", (e) => {
                if (e.target.matches(".precio")) recalcular();
            });

            recalcular();
        }

        function llenarParaEditar(boton, event) {
            let $btn = $(boton);

            let id = $btn.data('id') || '';
            let persona_id = $btn.data('persona') || '';
            let numero = $btn.data('numero') || '';
            let tipo_documento = $btn.data('tipo_documento') || '';
            let numero_documento = $btn.data('numero_documento') || '';

            let tipo_compra = $btn.data('tipo_compra') || '';
            let fecha_compra = $btn.data('fecha_compra') || '';
            let estado = $btn.data('estado') || '';
            let total = $btn.data('total') || '';

            $('#numero_ed').val(numero);
            $('#tipo_documento_ed').val(tipo_documento);
            $('#fecha_compra_ed').val(fecha_compra);
            $('#estado_ed').val(estado);
            $('#numero_documento_ed').val(numero_documento);
            $('#persona_id_ed').val(persona_id);
            $('#tipo_compra_ed').val(tipo_compra);
            $('#total_ed').val(total);


            // Modificar la acción del formulario para que apunte a la ruta de actualización
            let urlBase = "{{ route('compras.update', ['id' => 'ID_REEMPLAZAR']) }}";
            let nuevaUrl = urlBase.replace('ID_REEMPLAZAR', id);
            $('#formCompraeditar').attr('action', nuevaUrl);

            // Mostrar el modal
            $('#modalCompraeditar').modal('show');

            // Limpiar efectos en el botón (opcional)
            event.target.blur();
            event.target.classList.remove('active');
        }



        if ($(".msj").length) {
            setTimeout(() => {
                $(".msj").fadeOut();

            }, 3000);
        }
    </script>

    <script src="{{ asset('melody/data-table.js') }}"></script>
@endsection
