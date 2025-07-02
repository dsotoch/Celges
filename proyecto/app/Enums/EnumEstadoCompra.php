<?php

namespace App\Enums;

enum EnumEstadoCompra: string
{
    case Pendiente = 'pendiente';
    case Pagado = 'pagado';
    case Anulado = 'anulado';
}
