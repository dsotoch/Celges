<?php

namespace App\Enums;


enum EnumTipoDocumento: string
{
    case Boleta = 'boleta';
    case Factura = 'factura';
    case Otro = 'otro';
}

