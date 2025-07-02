<?php

namespace App\Enums;

enum EnumTipos: string
{
    case cliente = 'cliente';
    case ambos = 'ambos';
    case proveedor = 'proveedor';
}