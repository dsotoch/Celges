<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Cuentas Bancarias</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        h2, h4 {
            text-align: center;
            margin: 0;
        }
        .fecha {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #444;
            padding: 6px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        .total {
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Reporte de Cuentas Bancarias</h2>
    <h4>Del {{ $inicio }} al {{ $fin }}</h4>

    @foreach ($cuentas as $cuenta)
        <h4 style="margin-top: 25px;">{{ strtoupper($cuenta->tipo_cuenta) }} - {{ $cuenta->banco }}</h4>
        <p><strong>N° Cuenta:</strong> {{ $cuenta->numero_cuenta ?? '—' }}</p>
        <p><strong>Moneda:</strong> {{ $cuenta->moneda }}</p>

        @if ($cuenta->operacion->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th>Cuenta</th>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>N° Operación</th>
                        <th>Monto ({{ $cuenta->moneda }})</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach ($cuenta->operacion as $op)
                        <tr>
                            <td>{{ $cuenta->numero_cuenta ?? '—' }}</td>
                            <td>{{ $op->fecha }}</td>
                            <td>{{ $op->tipo }}</td>
                            <td>{{ $op->numero }}</td>
                            <td>{{ number_format($op->monto, 2) }}</td>
                        </tr>
                        @php $total += $op->monto; @endphp
                    @endforeach
                    <tr>
                        <td colspan="4" class="total">Total:</td>
                        <td><strong>{{ number_format($total, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        @else
            <p style="color: gray;">No hay operaciones registradas en este rango de fechas.</p>
        @endif
    @endforeach

    <footer style="text-align:center; margin-top:30px; font-size:11px;">
        Generado automáticamente el {{ now()->format('d/m/Y H:i') }}
    </footer>
</body>
</html>
