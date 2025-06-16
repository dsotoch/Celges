<?php

namespace App\Enums;

enum TipoVenta: string
{
    case Contado = 'Contado';
    case Credito = 'Credito';
    case Mixto = 'Mixto';
}
enum Estado: string
{

    case Pendiente = 'Pendiente';
    case Pagado = 'Pagado';
    case Anulado = 'Anulado';
}
