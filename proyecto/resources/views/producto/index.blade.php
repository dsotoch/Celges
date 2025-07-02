@extends('partials.layout')
@section('estilos')
@endsection
@section('pagina')
    <div class="content-wrapper">
        @component('componentes.com_titulo', [
            'titulo' => 'Productos',
            'paginaprincipal' => 'Productos',
            'paginaactual' => 'Productos',
        ])
        @endcomponent

        <div class="card">
            <div class="d-flex  align-items-center gap-3 p-2">
                <!-- Columna de la tarjeta -->
                <div class="col-12 col-md-8 grid-margin ">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-0">Cantidad de Productos</h4>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-inline-block pt-3">
                                    <div class="d-md-flex">
                                        <h2 class="mb-0">{{ $productos->count() }}</h2>
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
                    <button class="btn btn-primary rounded" data-toggle="modal" data-target="#modalRecurso">
                        <i class="fas fa-plus-circle mr-1"></i>
                        Nuevo Producto
                    </button>
                </div>

                <!---Inicio Modal registrar recurso--->
                <div class="modal fade" id="modalRecurso" tabindex="-1" role="dialog" aria-labelledby="modalRecursoLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Registrar Producto</h5>
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

                                <form id="formRecurso" method="POST" action="{{ route('productos.store') }}">
                                    @csrf

                                    <div class="row">
                                        <!-- Código -->
                                        <div class="col-md-6 mb-3">
                                            <label for="codigo">Código <span class="obligatorio">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                </div>
                                                <input type="text" name="codigo" id="codigo"
                                                    class="form-control @error('codigo') is-invalid @enderror"
                                                    value="{{ old('codigo', $codigo) }}" placeholder="Ingrese código"
                                                    readonly>
                                            </div>
                                            @error('codigo')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Tipo -->
                                        <div class="col-md-6 mb-3">
                                            <label for="tipo">Tipo <span class="obligatorio">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                                                </div>
                                                <input type="text" name="tipo" id="tipo"
                                                    class="form-control @error('tipo') is-invalid @enderror"
                                                    value="{{ old('tipo') ?? 'celular' }}" placeholder="Ingrese tipo">
                                            </div>
                                            @error('tipo')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Marca -->
                                        <div class="col-md-6 mb-3">
                                            <label for="marca">Marca <span class="obligatorio">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                </div>
                                                <input type="text" name="marca" id="marca"
                                                    class="form-control @error('marca') is-invalid @enderror"
                                                    value="{{ old('marca') }}" placeholder="Ingrese marca">
                                            </div>
                                            @error('marca')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Modelo -->
                                        <div class="col-md-6 mb-3">
                                            <label for="modelo">Modelo <span class="obligatorio">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-cogs"></i></span>
                                                </div>
                                                <input type="text" name="modelo" id="modelo"
                                                    class="form-control @error('modelo') is-invalid @enderror"
                                                    value="{{ old('modelo') }}" placeholder="Ingrese modelo">
                                            </div>
                                            @error('modelo')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Capacidad -->
                                        <div class="col-md-6 mb-3">
                                            <label for="capacidad">Capacidad <span class="obligatorio">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-database"></i></span>
                                                </div>
                                                <input type="text" name="capacidad" id="capacidad"
                                                    class="form-control @error('capacidad') is-invalid @enderror"
                                                    value="{{ old('capacidad') }}" placeholder="Ingrese capacidad">
                                            </div>
                                            @error('capacidad')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                            <i class="fas fa-times-circle mr-1"></i> Cancelar
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
                <!---Fin Modal registrar recurso--->

                <!-- Inicio Modal editar producto -->
                <div class="modal fade" id="modalRecursoeditar" tabindex="-1" role="dialog"
                    aria-labelledby="modalRecursoLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Modificar Producto</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                    <span aria-hidden="true">X</span>
                                </button>
                            </div>
                            <div class="modal-body">


                                <form id="formRecursoeditar" method="POST"
                                    action="{{ route('productos.update', ['id' => '0']) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <!-- Código -->
                                        <div class="col-md-6 mb-3">
                                            <label for="codigo_ed">Código <span class="obligatorio">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                                </div>
                                                <input type="text" name="codigo" id="codigo_ed"
                                                    class="form-control @error('codigo') is-invalid @enderror"
                                                    value="{{ old('codigo', $codigo) }}" placeholder="Ingrese código"
                                                    readonly>
                                            </div>
                                            @error('codigo')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Tipo -->
                                        <div class="col-md-6 mb-3">
                                            <label for="tipo_ed">Tipo <span class="obligatorio">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-mobile-alt"></i></span>
                                                </div>
                                                <input type="text" name="tipo" id="tipo_ed"
                                                    class="form-control @error('tipo') is-invalid @enderror"
                                                    value="{{ old('tipo') ?? 'celular' }}" placeholder="Ingrese tipo">
                                            </div>
                                            @error('tipo')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Marca -->
                                        <div class="col-md-6 mb-3">
                                            <label for="marca_ed">Marca <span class="obligatorio">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                </div>
                                                <input type="text" name="marca" id="marca_ed"
                                                    class="form-control @error('marca') is-invalid @enderror"
                                                    value="{{ old('marca') }}" placeholder="Ingrese marca">
                                            </div>
                                            @error('marca')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Modelo -->
                                        <div class="col-md-6 mb-3">
                                            <label for="modelo_ed">Modelo <span class="obligatorio">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-cogs"></i></span>
                                                </div>
                                                <input type="text" name="modelo" id="modelo_ed"
                                                    class="form-control @error('modelo') is-invalid @enderror"
                                                    value="{{ old('modelo') }}" placeholder="Ingrese modelo">
                                            </div>
                                            @error('modelo')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Capacidad -->
                                        <div class="col-md-6 mb-3">
                                            <label for="capacidad_ed">Capacidad <span class="obligatorio">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-database"></i></span>
                                                </div>
                                                <input type="text" name="capacidad" id="capacidad_ed"
                                                    class="form-control @error('capacidad') is-invalid @enderror"
                                                    value="{{ old('capacidad') }}" placeholder="Ingrese capacidad">
                                            </div>
                                            @error('capacidad')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                            <i class="fas fa-times-circle mr-1"></i> Cancelar
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
                    <div class="alert alert-success msj">{{ session('success_edit') }}</div>
                @endif
                @if ($errors->has('general_edit'))
                    <div class="alert alert-danger msj">{{ $errors->first('general_edit') }}</div>
                @endif

                {{ $productos->links() }}
                <br>
                <hr>
                <h4 class="card-title">Lista de Productos</h4>
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
                                                @foreach ($productos as $item)
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

    </div>
@endsection
@section('scripts')
    @if (session('show_modal') || session('success'))
        <script>
            $(document).ready(function() {
                $('#modalRecurso').modal('show');

            });
        </script>
    @endif
    @if (session('show_modal_edit'))
        <script>
            $(document).ready(function() {
                $('#modalRecursoeditar').modal('show');

            });
        </script>
    @endif
    <script>
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

            let id = $btn.data('id') || '';
            let codigo = $btn.data('codigo') || '';
            let tipo = $btn.data('tipo') || '';
            let marca = $btn.data('marca') || '';
            let modelo = $btn.data('modelo') || '';
            let capacidad = $btn.data('capacidad') || '';

            $('#codigo_ed').val(codigo);
            $('#tipo_ed').val(tipo);
            $('#marca_ed').val(marca);
            $('#modelo_ed').val(modelo);
            $('#capacidad_ed').val(capacidad);

            // Modificar la acción del formulario para que apunte a la ruta de actualización
            let urlBase = "{{ route('productos.update', ['id' => 'ID_REEMPLAZAR']) }}";
            let nuevaUrl = urlBase.replace('ID_REEMPLAZAR', id);
            $('#formRecursoeditar').attr('action', nuevaUrl);

            // Mostrar el modal
            $('#modalRecursoeditar').modal('show');

            // Limpiar efectos en el botón (opcional)
            event.target.blur();
            event.target.classList.remove('active');
        }


        function Modificar(event) {
            event.preventDefault();

            if (confirm(`¿Estás seguro de que deseas editar el registro?`)) {
                $('#modalRecursoeditar').modal('hide');

                event.target.closest('form').submit();
            }
            return false;
        }
        if ($(".msj").length) {
            setTimeout(() => {
                $(".msj").fadeOut();

            }, 3000);
        }
    </script>

    <script src="{{ asset('melody/data-table.js') }}"></script>
@endsection
