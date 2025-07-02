@extends('partials.layout')
@section('estilos')
@endsection
@section('pagina')
    <div class="content-wrapper" id="pagina_div">
        @component('componentes.com_titulo', [
            'titulo' => 'Almacen Interno',
            'paginaprincipal' => 'Almacen Interno',
            'paginaactual' => 'Stock Almacen Interno',
        ])
        @endcomponent

        <div class="card">
            <div class="d-flex  align-items-center gap-3 p-2">
                <!-- Columna de la tarjeta -->
                <div class="col-12 col-md-8 grid-margin ">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-0">Cantidad de Productos en Almacen</h4>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-inline-block pt-3">
                                    <div class="d-md-flex">
                                        <h2 class="mb-0">{{ $almaceninterno->sum('cantidad') }}</h2>
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
                <div class="alert alert-info mt-1" role="alert">
                    <strong>📌 Leyenda:</strong><br>
                    ✅ <strong>Registrado:</strong> Producto registrado.<br>
                    🎨 <strong>Color:</strong> Color del producto (ej. rojo, azul, etc).<br>
                    🔢 <strong>Stock:</strong> Total de unidades agrupadas por producto, color y estado.<br>
                    🖥️<strong>Acciones:</strong> Ver Detalles de los Productos.<br>
                </div>

                <hr>
                <h4 class="card-title">Lista de Productos en Almacen</h4>
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
                                                        aria-label="Purchased On: activate to sort column ascending"
                                                        style="width: 102.688px;">Marca</th>

                                                    <th class="sorting" tabindex="0" aria-controls="order-listing"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Ship to: activate to sort column ascending"
                                                        style="width: 54.6406px;">Modelo</th>
                                                    <th class="sorting" tabindex="0" aria-controls="order-listing"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Base Price: activate to sort column ascending"
                                                        style="width: 77.5156px;">Capacidad</th>
                                                    <th class="sorting" tabindex="0" aria-controls="order-listing"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Purchased Price: activate to sort column ascending"
                                                        style="width: 117.828px;">Color</th>
                                                    <th class="sorting" tabindex="0" aria-controls="order-listing"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Purchased Price: activate to sort column ascending"
                                                        style="width: 117.828px;">Stock</th>

                                                    <th class="sorting" tabindex="0" aria-controls="order-listing"
                                                        rowspan="1" colspan="1"
                                                        aria-label="Actions: activate to sort column ascending"
                                                        style="width: 58.75px;">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $agrupados = collect($almaceninterno)->groupBy(function ($item) {
                                                        return $item->producto_id .
                                                            '-' .
                                                            strtolower($item->color) .
                                                            '-' .
                                                            ($item->registrado ? '1' : '0');
                                                    });
                                                @endphp

                                                @foreach ($agrupados as $grupo)
                                                    @php
                                                        $primerItem = $grupo->first();
                                                        $cantidadTotal = $grupo->sum('cantidad');
                                                        $ids = $grupo->pluck('id');
                                                    @endphp
                                                    <tr role="row" class="{{ $loop->odd ? 'odd' : 'even' }}">
                                                        <td
                                                            class="sorting_1 {{ $primerItem->producto->tipo != 'CELULAR' ? 'badge bg-danger' : '' }}">
                                                            {{ $primerItem->producto->codigo }}</td>
                                                        <td>{{ $primerItem->producto->marca }}</td>

                                                        <td class="text-center">
                                                            <div class="d-flex flex-column align-items-center">
                                                                <span>{{ $primerItem->producto->modelo }}</span>
                                                                @if ($primerItem->registrado)
                                                                    <span class="badge bg-success mt-1">Registrado</span>
                                                                @endif
                                                            </div>
                                                        </td>


                                                        <td>{{ $primerItem->producto->capacidad }}</td>
                                                        <td>{{ strtoupper($primerItem->color) }}</td>
                                                        <td>{{ $cantidadTotal }}</td>

                                                        <td class="d-flex gap-2 align-items-center">
                                                            <button class="btn btn-outline-primary" type="button"
                                                                onclick="detalles({{ $ids }})">
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
    
    <script>
        function detalles(id) {
            $.ajax({
                url: '/almaceninterno/' + id,
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
    </script>




    <script>
    

        if ($(".msj").length) {
            setTimeout(() => {
                $(".msj").fadeOut();

            }, 3000);
        }
    </script>

    <script src="{{ asset('melody/data-table.js') }}"></script>
@endsection
