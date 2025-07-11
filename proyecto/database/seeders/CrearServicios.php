<?php

namespace Database\Seeders;

use App\Models\Servicio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CrearServicios extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Servicio::insert([
            [
                "nombre" => "Compra de productos",
                "descripcion" => "Adquisición de bienes o insumos necesarios para la operación."
            ],
            [
                "nombre" => "Pago a proveedores",
                "descripcion" => "Pagos pendientes o programados a proveedores locales."
            ],
            [
                "nombre" => "Cambio de moneda",
                "descripcion" => "Operaciones de cambio de divisas con cambistas."
            ],
            [
                "nombre" => "Alquiler de local",
                "descripcion" => "Pago mensual por alquiler de espacios físicos."
            ],
            [
                "nombre" => "Servicios de luz",
                "descripcion" => "Pago del recibo de energía eléctrica."
            ],
            [
                "nombre" => "Servicios de agua",
                "descripcion" => "Pago del recibo de agua potable."
            ],
            [
                "nombre" => "Internet y telefonía",
                "descripcion" => "Servicios de comunicación y conexión."
            ],
            [
                "nombre" => "Sueldos y remuneraciones",
                "descripcion" => "Pago mensual a los empleados."
            ],
            [
                "nombre" => "Servicios contables",
                "descripcion" => "Honorarios por servicios de contabilidad o auditoría."
            ],
            [
                "nombre" => "Mantenimiento de equipos",
                "descripcion" => "Servicio técnico y reparación de hardware o software."
            ],
            [
                "nombre" => "Publicidad y marketing",
                "descripcion" => "Gastos en anuncios, redes sociales y campañas."
            ],
            [
                "nombre" => "Transporte y logística",
                "descripcion" => "Gastos asociados al traslado de mercancías."
            ],
            [
                "nombre" => "Servicios legales",
                "descripcion" => "Honorarios por asesoría o defensa legal."
            ],
            [
                "nombre" => "Capacitación y desarrollo",
                "descripcion" => "Cursos y talleres para el personal."
            ],
            [
                "nombre" => "Otros",
                "descripcion" => "Cualquier otro servicio no clasificado anteriormente."
            ]
        ]);
    }
}
