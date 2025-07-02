<?php

namespace App\Enums;

enum EnumTipoCompra: string
{
    case Contado = 'contado';
    case Credito = 'credito';
    case Mixto = 'mixto';
}