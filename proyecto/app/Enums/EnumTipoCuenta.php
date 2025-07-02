<?php

namespace App\Enums;

enum EnumTipoCuenta: string
{
    case Ahorros = 'Ahorros';
    case Corriente = 'Corriente';
    case CCI = 'CCI';
    case PlazoFijo = 'Plazo Fijo';
    case Remuneraciones = 'Remuneraciones';
    case Yape = 'Yape';
    case Plin = 'Plin';
    case TransferenciaDigital = 'Transferencia Digital';
    case Otros = 'Otros';
}