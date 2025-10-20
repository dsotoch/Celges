<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario de Productos</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: center; }
        th { background-color: #f2f2f2; }
        .bg-warning { background-color: #ffc107; }
        .bg-primary { background-color: #007bff; color: #fff; }
        .bg-success { background-color: #28a745; color: #fff; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Inventario de Productos</h2>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Capacidad</th>
                <th>Color</th>
                <th>Stock</th>
                <th>Registrado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($agrupados as $grupo)
                @php
                    $primerItem = $grupo->first();
                    $cantidadTotal = $grupo->sum('cantidad');
                    $clase = match ($primerItem->producto->tipo) {
                        'TABLET' => 'bg-warning',
                        'OTRO' => 'bg-primary',
                        default => '',
                    };
                @endphp
                <tr>
                    <td class="{{ $clase }}">{{ $primerItem->producto->codigo }}</td>
                    <td>{{ $primerItem->producto->marca }}</td>
                    <td>{{ $primerItem->producto->modelo }}</td>
                    <td>{{ $primerItem->producto->capacidad }}</td>
                    <td>{{ strtoupper($primerItem->color) }}</td>
                    <td>{{ $cantidadTotal }}</td>
                    <td>
                        @if ($primerItem->registrado)
                            <span class="bg-success">Sí</span>
                        @else
                            No
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
