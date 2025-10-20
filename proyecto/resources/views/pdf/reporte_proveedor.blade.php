<h2>Reporte del Proveedor: {{ $cliente->nombres }}</h2>
<p>Rango de fechas: {{ $fecha_inicio }} - {{ $fecha_fin }}</p>
<hr>

@foreach ($ventas as $venta)
    <h4>Compra #{{ $venta->id }} - {{ $venta->fecha_compra }}</h4>

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
            @foreach ($venta->detalle as $detalle)
                <tr>
                    <td>{{ $detalle->producto->marca }} {{ $detalle->producto->modelo }}
                        {{ $detalle->producto->capacidad }}</td>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>{{ number_format($detalle->precio, 2) }}</td>
                    <td>{{ number_format($detalle->cantidad * $detalle->precio, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Total compra:</strong> S/ {{ number_format($venta->total, 2) }}</p>

    @php
        $pagosVenta = $pagos->where('nota', $venta->id);
    @endphp

    @if ($pagosVenta->count() > 0)
        <h5>Pagos realizados:</h5>
        <table border="1" cellspacing="0" cellpadding="5" width="100%">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Monto</th>
                    <th>Metodo</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pagosVenta as $pago)
                    <tr>
                        <td>{{ $pago->fecha_pago }}</td>
                        <td>S/ {{ number_format($pago->monto_pagado, 2) }}</td>
                        <td>{{ $pago->metodo_pago}} {{ $pago->operacion->cuenta?->banco}} {{ $pago->operacion->cuenta?->tipo_cuenta}} {{ $pago->operacion->cuenta?->numero_cuenta}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p><strong>Total Pagado:</strong> S/ {{ number_format($pagosVenta->sum('monto_pagado'), 2) }}</p>
        <p><strong>Saldo Pendiente:</strong> S/
            {{ number_format($venta->total - $pagosVenta->sum('monto_pagado'), 2) }}</p>
    @else
        <p><em>No se registran pagos para esta compra.</em></p>
    @endif

    <hr>
@endforeach
