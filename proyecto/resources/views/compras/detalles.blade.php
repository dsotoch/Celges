<div class="container mt-3">
    <button onclick="window.location='/compras'" class="btn btn-outline-primary btn-sm rounded-pill shadow-sm mb-4">
        <i class="fas fa-arrow-left mr-2"></i> Atrás
    </button>
    <div class="card">
        <div class="card-header border text-black d-flex justify-between">

            @php
                $estadoClase = match ($compra->estado) {
                    'pagado' => 'p-2 bg-success text-white',
                    'pendiente' => 'p-2 bg-warning text-dark',
                    'anulado' => 'p-2 bg-danger text-white',
                    default => 'bg-secondary text-white',
                };
            @endphp

            <div class="{{ $estadoClase }}">
                {{ strtoupper($compra->estado) }}
            </div>
            <div>
                <h5 class="mb-0">Detalles de la Compra #{{ $compra->numero }}</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4 mb-3 mb-md-0">
                    <strong>Fecha de Compra:</strong>
                    <span id="fechaCompra">{{ $compra->fecha_compra }}</span>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <strong>Documento:</strong>
                    <span id="documento">{{ $compra->numero_documento }}</span>
                </div>
                <div class="col-md-4 text-md-right">
                    <strong>Proveedor:</strong>
                    <span id="proveedor">{{ $compra->persona->nombres }}</span>
                </div>
            </div>


            <div class="table-responsive">
                <form action="{{ route('compras.updatecolor') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detallescompra as $item)
                                <tr>
                                    <td class="d-flex align-items-center gap-2">
                                        <input type="hidden" value="{{ $item->id }}" name="id[]">
                                        <span>{{ $item->producto->marca }} {{ $item->producto->modelo }}
                                            {{ $item->producto->capacidad }}</span>
                                        <span>|| {{ $item->imei }} ||</span>
                                        <input type="text" name="color[]" class="form-control form-control-sm w-auto"
                                            value="{{ $item->color }}"
                                            @if (!empty($item->color) && $item->color != '-') readonly @endif>
                                    </td>

                                    <td class="cantidad">{{ $item->cantidad }}</td>
                                    <td>
                                        <input type="text" name="precio[]" class="form-control precio"
                                            value="{{ number_format($item->precio, 2) }}">
                                    </td>
                                    <td class="subtotal">{{ number_format($item->precio * $item->cantidad, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-right">Total:</th>
                                <th id="totalFinal">S/ {{ number_format($compra->total, 2) }}</th>
                            </tr>
                        </tfoot>

                        
                    </table>
                    <div class="text-end mt-3 mb-2">
                        <button type="submit" class="btn bg-orange">Modificar</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    
</div>

