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
                                                    class="obligatorio">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                </div>
                                                <input type="text" name="numero_documento" id="numero_documento"
                                                    class="form-control @error('numero_documento') is-invalid @enderror"
                                                    value="{{ old('numero_documento') }}" placeholder="Ej: F001-000123">
                                            </div>
                                            @error('numero_documento')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Total -->
                                        <div class="col-md-6 mb-3">
                                            <label for="total">Total (S/.) <span class="obligatorio">*</span></label>
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
                                                        <option value="{{ $producto->id }}">
                                                            {{ $producto->marca }} {{ $producto->modelo }}
                                                            {{ $producto->capacidad }}
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
                                                    placeholder="IMEI del producto">
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
                                                    placeholder="Color del producto">
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
                                                    class="form-control" placeholder="Precio">
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
                                                <button type="button" type="button" onclick="agregarProducto()"
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
                                                    class="obligatorio">*</span></label>
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
                                            <label for="total">Total (S/.) <span class="obligatorio">*</span></label>
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
                                                        <td>{{ $item->fecha_compra }}</td>
                                                        <td>{{ $item->total }}</td>
                                                        <td class="d-flex gap-2 align-items-center">
                                                            <form
                                                                action="{{ route('compras.destroy', ['id' => $item->id]) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-outline-primary"
                                                                    onclick="return eliminar(event,'{{ $item->numero }}')">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>

                                                            <button class="btn btn-outline-primary" {{$item->estado=="anulado"?"disabled":""}}
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
        function detalles(id) {
            $.ajax({
                url: '/compras/' + id,
                method: 'GET',
                dataType: 'html',
                success: function(response) {
                    $('#pagina_div').html(response);
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

        $('#imei').on('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });
        var detallesProductos = [];

        function agregarProducto() {
            const ulresultados = $('#detalles_productos');
            const producto_text = $('#producto_id option:selected').text();
            const producto_id = $('#producto_id').val();
            const imei = $('#imei').val();
            const color = $('#color').val();
            const precio = $('#precio').val();
            const cantidad = $('#cantidad').val();
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

            actualizarListaVisual();

            // Limpiar campos
            $('#producto_id').val('');
            select.clear(); // si usas TomSelect
            $('#imei').val('');
            $('#color').val('');
            $('#precio').val('');
            $('#cantidad').val('1');
            $('#registrado').prop('checked', false);
        }

        function eliminarItem(imeiBuscado) {
            detallesProductos = detallesProductos.filter(item => item.imei !== imeiBuscado);
            actualizarListaVisual();
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

            if (confirm(`¿Estás seguro de que deseas eliminar a ${nombre}?`)) {

                event.target.closest('form').submit();
            }
            event.target.blur();
            event.target.classList.remove('active');
            return false;

        }

        function guardar(event) {
            event.preventDefault();

            if (confirm(`¿Estás seguro de que deseas guardar esta Compra despues de haber revisado todos los detalles.?`)) {

                event.target.closest('form').submit();
            }
            return false;
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
