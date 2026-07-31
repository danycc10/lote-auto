<?php

namespace App\Enums;

enum ReporteGeneradoEstatus: string
{
    case Pendiente = 'pendiente';
    case Procesando = 'procesando';
    case Listo = 'listo';
    case Fallido = 'fallido';
}
