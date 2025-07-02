<div class="container mt-3">
    <button onclick="window.location='/almaceninterno'"
        class="btn btn-outline-primary btn-sm rounded-pill shadow-sm mb-4">
        <i class="fas fa-arrow-left mr-2"></i> Atrás
    </button>
    <div class="card">
        <div class="card-header border text-black d-flex justify-between">
            <div>
                <h5 class="mb-0">Detalles de los Productos</h5>
            </div>
        </div>
        <div class="card-body">


            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Proveedor</th>
                            <th>Modelo</th>
                            <th>Registrado</th>
                            <th>Stock</th>
                            <th>IMEI</th>
                            <th>Precio Compra</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($almaceninterno as $item)
                            <tr>
                                <td>{{$item->compra->persona->nombres}}</td>
                                <td>{{ $item->producto->marca }} {{ $item->producto->modelo }}
                                    {{ $item->producto->capacidad }}</td>
                                <td>
                                    @if ($item->registrado)
                                        <span class="badge badge-success">Sí</span>
                                    @else
                                        <span class="badge badge-secondary">No</span>
                                    @endif
                                </td>
                                <td>{{ $item->cantidad }}</td>
                                <td>{{ $item->imei }}</td>
                                <td>S/ {{ number_format($item->precio_compra, 2) }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
