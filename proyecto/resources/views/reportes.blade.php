@extends('partials.layout')
@section('estilos')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
@endsection
@section('pagina')
    <div class="content-wrapper container ">
        <h4 class="mb-4">📊 Reporte de Ventas</h4>

        <!-- Filtros -->
        <form id="filtroForm" class="form-inline mb-4" method="GET" action="{{ route('dashboard.reportes') }}">
            <button type="submit" name="semana" value="1" class="btn btn-primary mr-2">Última Semana</button>

            <div class="form-group mr-2">
                <label for="desde" class="mr-2">Desde:</label>
                <input type="date" class="form-control" name="desde" id="desde" value="{{ request('desde') }}">
            </div>

            <div class="form-group mr-2">
                <label for="hasta" class="mr-2">Hasta:</label>
                <input type="date" class="form-control" name="hasta" id="hasta" value="{{ request('hasta') }}">
            </div>

            <button type="submit" class="btn btn-success">Filtrar</button>
        </form>


        <!-- Tabla de ventas -->
        <div class="table-responsive">
            <table id="tablaVentas" class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Monto</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ventas as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item['fecha'] ?? $item->fecha }}</td>
                            <td>{{ $item['cliente']['nombres'] ?? $item->cliente }}</td>
                            <td>S/ {{ number_format($item['total'] ?? $item->total, 2) }}</td>
                            <td>{{ $item['estado'] ?? $item->estado }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right font-weight-bold">
                            <i class="fas fa-cash-register text-success mr-1"></i> MONTO TOTAL:
                        </td>
                        <td colspan="1" class=" text-white text-center" style="font-size: 18px; background-color: orangered">
                            <span id="totalVentas">0.00</span>
                        </td>
                    </tr>
                </tfoot>

            </table>
        </div>

    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            let tabla = $('#tablaVentas').DataTable({
                "language": {
                    url: "../esdatatable.json"
                },
                order: [
                    [0, 'desc']
                ],
                lengthMenu: [
                    [50, 100, -1], // Valores reales (-1 significa "Todos")
                    [50, 100, "Todos"] // Etiquetas visibles
                ],
                pageLength: 50,
                initComplete: function() {
                    actualizarTotal();
                }
            });

            tabla.on('draw', function() {
                actualizarTotal();
            });

            function actualizarTotal() {
                let total = 0;
                // Recorre solo las filas visibles
                tabla.rows({
                    search: 'applied'
                }).every(function() {
                    let data = this.data();
                    let valorCol3 = data[3]; // Columna 3
                    let monto = parseFloat(valorCol3.replace('S/', '').replace(',', '').trim()) || 0;
                    total += monto;
                });

                $('#totalVentas').text('S/ ' + total.toFixed(2));
            }
        });
    </script>
@endsection
