<?php

namespace App\Enums;

enum TipoCompra: string
{
    case Contado = 'contado';
    case Credito = 'credito';
    case Mixto = 'mixto';
}
enum TipoDocumento: string
{
    case Boleta = 'boleta';
    case Factura = 'factura';
    case Otro = 'otro';
}
enum EstadoCompra: string
{
    case Pendiente = 'pendiente';
    case Pagado = 'pagado';
    case Anulado = 'anulado';
}
