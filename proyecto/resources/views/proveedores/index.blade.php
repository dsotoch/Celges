@extends('partials.layout')
@section('estilos')
@endsection
@section('pagina')
    <div class="content-wrapper">
        @component('componentes.com_titulo', [
            'titulo' => 'Proveedores',
            'paginaprincipal' => 'Proveedores',
            'paginaactual' => 'Proveedores',
        ])
        @endcomponent

        <div class="card pt-4">
            <div class="row align-items-center p-2">
                <!-- Columna de la tarjeta -->
                <div class="col-12 col-md-8 grid-margin ">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-0">Cantidad de Proveedores</h4>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-inline-block pt-3">
                                    <div class="d-md-flex">
                                        <h2 class="mb-0">{{ $personas->count() }}</h2>
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
                    <button class="btn btn-primary rounded" data-toggle="modal" data-target="#modalProveedor">
                        <i class="fas fa-plus-circle mr-1"></i>
                        Nuevo Proveedor
                    </button>
                </div>
                <!---Inicio Modal registrar proveedor--->
                <div class="modal fade" id="modalProveedor" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Registrar Proveedor</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">X</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                @if (session('success'))
                                    <div class="alert alert-success msj">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                @if ($errors->has('general'))
                                    <div class="alert alert-danger msj">
                                        {{ $errors->first('general') }}
                                    </div>
                                @endif

                                <form id="formProveedor" method="POST" action="{{ route('proveedores.store') }}">
                                    @csrf

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
                                                    <input type="text" name="codigo" id="codigo"
                                                        class="form-control @error('codigo') is-invalid @enderror"
                                                        value="{{ old('codigo', $codigo) }}" placeholder="Ingrese código"
                                                        readonly>
                                                    @error('codigo')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
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
                                                        value="{{ old('nombres') }}"
                                                        placeholder="Ingrese nombre del proveedor">
                                                    @error('nombres')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
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
                                                    @error('ruc')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
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
                                                    @error('direccion')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
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
                                                    @error('telefono')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
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
                                                        value="{{ old('email') }}"
                                                        placeholder="Ingrese correo electrónico">
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
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
                                                        @if ($tipos)
                                                            <option value="3">PROVEEDOR</option>
                                                        @else
                                                            <option value="">-- No Existen Tipos Disponibles --
                                                            </option>
                                                        @endif
                                                        {{--  @foreach ($tipos as $tipo)
                                                            <option value="{{ $tipo->id }}"
                                                                {{ old('tipo_id') == $tipo->id ? 'selected' : '' }}>
                                                                {{ strtoupper($tipo->tipo->value) }}
                                                            </option>
                                                        @endforeach
                                                        --}}
                                                    </select>
                                                    @error('tipo_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                            Cancelar
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save mr-1"></i> Guardar
                                        </button>
                                    </div>
                                </form>

                            </div>

                        </div>
                    </div>
                </div>
                <!--fin Modal Registrar proveedor -->

                <!---Inicio Modal editar proveedor--->

                <div class="modal fade" id="modalProveedoreditar" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabeleditar" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabeleditar">Editar Proveedor</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">X</span>
                                </button>
                            </div>
                            <div class="modal-body">


                                <form id="formProveedorEditar" method="POST" action="">
                                    @method('PUT')
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <!-- Código -->
                                            <div class="col-12 col-md-6 mb-3">
                                                <label for="codigo_ed">Código</label> <span class="obligatorio">*</span>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-code"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" name="codigo" id="codigo_ed"
                                                        class="form-control @error('codigo') is-invalid @enderror"
                                                        value="{{ old('codigo', $codigo) }}" placeholder="Ingrese código"
                                                        readonly>
                                                    @error('codigo')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Nombres -->
                                            <div class="col-12 col-md-6 mb-3">
                                                <label for="nombres_ed">Nombres</label> <span class="obligatorio">*</span>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-user"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" name="nombres" id="nombres_ed"
                                                        class="form-control @error('nombres') is-invalid @enderror"
                                                        value="{{ old('nombres') }}"
                                                        placeholder="Ingrese nombre del proveedor">
                                                    @error('nombres')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- RUC -->
                                            <div class="col-12 col-md-6 mb-3">
                                                <label for="ruc_ed">RUC</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-id-card"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" name="ruc" id="ruc_ed"
                                                        class="form-control @error('ruc') is-invalid @enderror"
                                                        value="{{ old('ruc') }}" placeholder="Ingrese RUC">
                                                    @error('ruc')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Dirección -->
                                            <div class="col-12 col-md-6 mb-3">
                                                <label for="direccion_ed">Dirección</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-map-marker-alt"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" name="direccion" id="direccion_ed"
                                                        class="form-control @error('direccion') is-invalid @enderror"
                                                        value="{{ old('direccion') }}" placeholder="Ingrese dirección">
                                                    @error('direccion')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Teléfono -->
                                            <div class="col-12 col-md-6 mb-3">
                                                <label for="telefono_ed">Teléfono</label> <span
                                                    class="obligatorio">*</span>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-phone"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" name="telefono" id="telefono_ed"
                                                        class="form-control @error('telefono') is-invalid @enderror"
                                                        value="{{ old('telefono') }}" placeholder="Ingrese teléfono">
                                                    @error('telefono')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Email -->
                                            <div class="col-12 col-md-6 mb-3">
                                                <label for="email_ed">Email</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-envelope"></i>
                                                        </span>
                                                    </div>
                                                    <input type="email" name="email" id="email_ed"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        value="{{ old('email') }}"
                                                        placeholder="Ingrese correo electrónico">
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!-- Tipo -->
                                            <div class="col-12 col-md-6 mb-3">
                                                <label for="tipo_id_ed">Tipo</label> <span class="obligatorio">*</span>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="fas fa-list"></i>
                                                        </span>
                                                    </div>
                                                    <select name="tipo_id" id="tipo_id_ed"
                                                        class="form-control  @error('tipo_id') is-invalid @enderror">

                                                        @foreach ($tipos as $tipo)
                                                            <option value="{{ $tipo->id }}"
                                                                {{ old('tipo_id') == $tipo->id ? 'selected' : '' }}>
                                                                {{ strtoupper($tipo->tipo->value) }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                    @error('tipo_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                            Cancelar
                                        </button>
                                        <button type="submit" class="btn btn-primary" onclick="Modificar(event)">
                                            <i class="fas fa-save mr-1"></i> Modificar
                                        </button>
                                    </div>
                                </form>

                            </div>

                        </div>
                    </div>
                </div>
                <!--Fin modal editar proveedor-->
            </div>

            <hr>
            <div class="card-body">
                @if (session('success_edit'))
                    <div class="alert alert-success mb-4 msj">
                        {{ session('success_edit') }}
                    </div>
                @endif
                @if ($errors->has('general_edit'))
                    <div class="alert alert-danger mb-4 msj">
                        {{ $errors->has('general_edit') }}
                    </div>
                @endif
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
                <h4 class="card-title">Lista de Proveedores</h4>

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
                                                        aria-label="Customer: activate to sort column ascending"
                                                        style="width: 72.3281px;">Nombres</th>
                                                    <th class="sorting" tabindex="0" aria-controls="order-listing"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Ship to: activate to sort column ascending"
                                                        style="width: 54.6406px;">Ruc</th>
                                                    <th class="sorting" tabindex="0" aria-controls="order-listing"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Base Price: activate to sort column ascending"
                                                        style="width: 77.5156px;">Direccion</th>
                                                    <th class="sorting" tabindex="0" aria-controls="order-listing"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Purchased Price: activate to sort column ascending"
                                                        style="width: 117.828px;">Telefono</th>
                                                    <th class="sorting" tabindex="0" aria-controls="order-listing"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Purchased Price: activate to sort column ascending"
                                                        style="width: 117.828px;">Email</th>
                                                    <th class="sorting" tabindex="0" aria-controls="order-listing"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Actions: activate to sort column ascending"
                                                        style="width: 58.75px;">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($personas as $item)
                                                    <tr role="row" class="{{ $loop->odd ? 'odd' : 'even' }}">
                                                        <td class="sorting_1">{{ $item->codigo }}</td>
                                                        <td>{{ $item->nombres }}</td>
                                                        <td>{{ $item->ruc ?? '--' }}</td>
                                                        <td>{{ $item->direccion ?? '--' }}</td>
                                                        <td>{{ $item->telefono }}</td>
                                                        <td>{{ $item->email ?? '--' }}</td>

                                                        <td class="d-flex gap-2 align-items-center">
                                                            <form
                                                                action="{{ route('proveedores.destroy', ['id' => $item->id]) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-outline-primary"
                                                                    onclick="return eliminar(event, '{{ $item->nombres }}')">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>

                                                            <button class="btn btn-outline-primary"
                                                                onclick="llenarParaEditar(this,event)"
                                                                data-id="{{ $item->id }}"
                                                                data-codigo="{{ $item->codigo }}"
                                                                data-nombres="{{ $item->nombres }}"
                                                                data-ruc="{{ $item->ruc ?? '' }}"
                                                                data-direccion="{{ $item->direccion ?? '' }}"
                                                                data-telefono="{{ $item->telefono }}"
                                                                data-email="{{ $item->email ?? '' }}"
                                                                data-tipo="{{ $item->tipo_id }}">
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

    </div>
@endsection
@section('scripts')
    @if (session('show_modal') || session('success'))
        <script>
            $(document).ready(function() {
                $('#modalProveedor').modal('show');

            });
        </script>
    @endif
    @if (session('show_modal_edit'))
        <script>
            $(document).ready(function() {
                $('#modalProveedoreditar').modal('show');

            });
        </script>
    @endif

    <script>
        if ($(".msj").length) {
            setTimeout(() => {
                $(".msj").fadeOut();

            }, 3000);
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

        function llenarParaEditar(boton, event) {
            let $btn = $(boton);

            // Obtener los valores desde data-attributes
            let codigo = $btn.data('codigo') || '';
            let nombres = $btn.data('nombres') || '';
            let ruc = $btn.data('ruc') || '';
            let direccion = $btn.data('direccion') || '';
            let telefono = $btn.data('telefono') || '';
            let email = $btn.data('email') || '';
            let tipo = $btn.data('tipo') || '';
            let id = $btn.data('id') || '';

            // Rellenar los campos del formulario
            $('#codigo_ed').val(codigo);
            $('#nombres_ed').val(nombres);
            $('#ruc_ed').val(ruc === '--' ? '' : ruc);
            $('#direccion_ed').val(direccion === '--' ? '' : direccion);
            $('#telefono_ed').val(telefono);
            $('#email_ed').val(email === '--' ? '' : email);
            $('#tipo_id_ed').val(tipo);

            // Mostrar el modal
            let urlBase = "{{ route('proveedores.update', ['id' => 'ID_REEMPLAZAR']) }}";
            let nuevaUrl = urlBase.replace('ID_REEMPLAZAR', id);
            $('#formProveedorEditar').attr('action', nuevaUrl);

            // Mostrar el modal
            $('#modalProveedoreditar').modal('show');
            event.target.blur();
            event.target.classList.remove('active');
        }

        function Modificar(event) {
            event.preventDefault();

            if (confirm(`¿Estás seguro de que deseas editar el registro?`)) {

                event.target.closest('form').submit();
            }
            return false;
        }
    </script>

    <script src="{{ asset('melody/data-table.js') }}"></script>
@endsection
