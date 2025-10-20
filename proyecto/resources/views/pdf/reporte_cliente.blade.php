<h2>Reporte del cliente: {{ $cliente->nombres }}</h2>
<p>Rango de fechas: {{ $fecha_inicio }} - {{ $fecha_fin }}</p>
<hr>

@foreach ($ventas as $venta)
    <h4>Venta #{{ $venta->id }} - {{ $venta->fecha }}</h4>
    <table border="1" cellspacing="0" cellpadding="5" width="100%">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($venta->detalles as $detalle)
                <tr>
                    <td>{{ $detalle->producto->marca}} {{ $detalle->producto->modelo}} {{ $detalle->producto->capacidad}}</td>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>{{ number_format($detalle->precio_unitario, 2) }}</td>
                    <td>{{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p><strong>Total: </strong>{{ number_format($venta->total, 2) }}</p>
    <hr>
@endforeach
