<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        @page {
            margin: 1cm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            margin: 0;
            color: #000;
        }

        .invoice-container {
            width: 100%;
            padding: 10px 20px;
            box-sizing: border-box;
        }

        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #0b63a8;
            padding-bottom: 8px;
        }

        .company {
            width: 60%;
        }

        .company h2 {
            color: #0b63a8;
            margin: 0;
            font-size: 20px;
            font-weight: bold;
        }

        .company p {
            margin: 2px 0;
            line-height: 1.3;
        }

        .invoice-info {
            text-align: right;
            font-size: 12px;
        }

        .invoice-info h3 {
            margin: 0;
            color: #0b63a8;
            font-size: 16px;
            text-transform: uppercase;
        }

        .box-group {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .box {
            border: 1px solid #000;
            padding: 8px;
            width: 50%;
            min-height: 70px;
        }

        .box h4 {
            margin: 0 0 5px;
            font-size: 13px;
            font-weight: bold;
        }

        .box p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        thead {
            background-color: #0b63a8;
            color: white;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 12px;
        }

        th {
            text-align: center;
        }

        td:nth-child(3),
        td:nth-child(4),
        td:nth-child(5) {
            text-align: center;
        }

        .notes {
            font-size: 10px;
            margin-top: 15px;
        }

        .totals {
            width: 40%;
            float: right;
            border: 1px solid #000;
            margin-top: 10px;
        }

        .totals tr td {
            border: 1px solid #000;
            padding: 6px;
        }

        .totals .total {
            background-color: #0b63a8;
            color: white;
            font-weight: bold;
        }

        footer {
            margin-top: 40px;
            text-align: right;
            font-size: 12px;
        }

        footer .signature {
            margin-top: 40px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="invoice-container">

        <div class="header">
            <img style="max-height: 100px;max-width: 200px"
                    src="https://jamb.pe/wp-content/uploads/2020/08/JAMB-TEHNOLOGY-CALIDAD-Y-GARANTIA-A-TU-SERVICIO-01.svg"
                    alt="">

                <p>
                    Contacto: {{ $numeros->numero1 ?? '' }} &nbsp;&nbsp;
                    Contacto: {{ $numeros->numero2 ?? '' }}
                </p>
                
           
            <div class="invoice-info">
                <h3>VENTA</h3>
                <p><strong>VENTA N°:</strong> {{ $venta->codigo }}</p>
                <p><strong>FECHA:</strong>{{ $venta->fecha }}</p>
                <p><strong>TIPO:</strong> {{ $venta->tipo_venta }}</p>
                <p><strong>ESTADO:</strong> {{ $venta->estado }}</p>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
            <tbody>
                <tr>
                    <td style="border: 1px solid #000; padding: 8px; width: 50%; vertical-align: top;">
                        <h4 style="margin: 0 0 5px 0;">CLIENTE:</h4>
                        <p style="margin: 0;">{{ $venta->cliente->nombres }}</p>
                    </td>
                    <td style="border: 1px solid #000; padding: 8px; width: 50%; vertical-align: top;">
                        <h4 style="margin: 0 0 5px 0;">DESTINO:</h4>
                        <p style="margin: 0;">{{ strtoupper($venta->destino) }}</p>
                    </td>
                </tr>
            </tbody>
        </table>



        <table>
            <thead>
                <tr>
                    <th>Cantidad</th>
                    <th>Descripcion</th>
                    <th>Costo Unitario</th>
                    <th>Sub Total</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($venta->detalles as $item)
                    @php
                        $producto =
                            $item->producto->modelo .
                            ' ' .
                            $item->producto->marca .
                            ' ' .
                            $item->producto->capacidad .
                            ' ' .
                            strtoupper($item->color) .
                            ' ' .
                            ($item->registrado == 1 ? 'REGISTRADO' : 'LIBRE');
                    @endphp
                    <tr>
                        <td>{{ $item->cantidad }}</td>
                        <td>{{ $producto }}</td>
                        <td>{{ $item->precio_unitario }}</td>
                        <td>{{ $item->subtotal }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>


        <table class="totals">
            <tr>
                <td>Sub Total</td>
                <td align="right">{{ $venta->subtotal }}</td>
            </tr>
            <tr>
                <td>Gasto de Envio</td>
                <td align="right">{{ $venta->envio }}</td>
            </tr>
            <tr>
                <td>Pago de Encomienda</td>
                <td align="right">{{ $venta->gasto_envio }}</td>
            </tr>
            <tr>
                <td>Saldo a favor</td>
                <td align="right">{{ $venta->saldo_a_favor }}</td>
            </tr>
            <tr>
                <td>Saldo Pendiente</td>
                <td align="right">{{ $venta->saldo_pendiente }}</td>
            </tr>
            <tr>
                <td>Facturacion</td>
                <td align="right">{{ $venta->comision_facturacion }}</td>
            </tr>
            <tr>
                <td>Registro de Equipo</td>
                <td align="right">{{ $venta->totalregistro }}</td>
            </tr>
            <tr class="total">
                <td>TOTAL</td>
                <td align="right">{{ $venta->total }}</td>
            </tr>
        </table>

        <div class="notes">
            <strong>Notas Importantes:</strong><br>
            • Garantía de 6 meses en todos nuestros equipos, contada a partir de la fecha de compra.<br>
            • Cambio inmediato dentro de los 3 días siguientes a la compra, si el equipo está en su embalaje
            original.<br>
            • No se aceptan devoluciones de equipos.<br>
            • No hacemos reintegro de dinero.<br>
            • Los precios pueden variar según disponibilidad de stock.<br>
            • Para Asegurar una entrega rapida y eficiente a la empresa de carga, es importante tener en cuenta nuestros
            horarios de recepcion de pedidos y pagos:<br>
            • Recepcion de pedidos: Desde las 10:00 am hasta las 05:30 pm<br>
            • Pago de pedidos: Desde las 10:00 am hasta las 06:00 pm<br>
            • Si tu pedido y pago son recibidos dentro de estos horarios, nos esforzaremos por enviar su pedido el mismo
            dia. De lo contrario, su pedido sera enviado el siguiente dia util.<br>
            Agradecemos su compresión y cooperacióm en este proceso. Si tiene alguna duda o inquietud, no dude en
            contactarnos.

        </div>

        <footer>
            @php
                $total = 0;
            @endphp

            <table style="width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 13px;">
                <thead style="background: #ff9898;">
                    <tr>
                        <th style="border: 1px solid #000; padding: 5px;">Fecha</th>
                        <th style="border: 1px solid #000; padding: 5px;">Medio / Cuenta</th>
                        <th style="border: 1px solid #000; padding: 5px;">N° Operación</th>
                        <th style="border: 1px solid #000; padding: 5px; text-align: right;">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($venta->abonos ?? [] as $item)
                        @php
                            $total += $item->monto ?? 0;
                        @endphp
                        <tr>
                            <td style="border: 1px solid #000; padding: 5px;">{{ $item->fecha }}</td>
                            <td style="border: 1px solid #000; padding: 5px;">
                                {{ $item->metodo_pago }}
                                {{ $item->operacion->cuenta->tipo_cuenta ?? '' }}
                                {{ $item->operacion->cuenta->numero_cuenta ?? '' }}
                                {{ $item->operacion->cuenta->titular ?? '' }}
                            </td>
                            <td style="border: 1px solid #000; padding: 5px;">{{ $item->operacion->numero ?? '' }}</td>
                            <td style="border: 1px solid #000; padding: 5px; text-align: right;">
                                S/ {{ number_format($item->monto ?? 0, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4"
                                style="border: 1px solid #000; padding: 5px; text-align: center; color: #777;">
                                No hay abonos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                @php
                    $totalpendiente = ($venta->total ?? 0) - $total;
                @endphp

                <tfoot>
                    <tr>
                        <td colspan="3" style="border: 1px solid #000; padding: 5px; font-weight: bold;">Total Abonos
                        </td>
                        <td style="border: 1px solid #000; padding: 5px; text-align: right; font-weight: bold;">
                            S/ {{ number_format($total, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="border: 1px solid #000; padding: 5px; font-weight: bold;">Total
                            Pendiente</td>
                        <td style="border: 1px solid #000; padding: 5px; text-align: right; font-weight: bold;">
                            S/ {{ number_format($totalpendiente, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>

        </footer>

    </div>
</body>

</html>
