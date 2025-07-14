<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        Permission::query()->delete();
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $modulos = [
            'dashboard',
            'caja',
            'cotizaciones',
            'ventas',
            'cuentas',
            'cuentasbancarias',
            'clientes',
            'compras',
            'pagos',
            'productos',
            'proveedores',
            'almacen',
        ];

        $acciones = ['ver', 'crear', 'editar', 'eliminar'];

        foreach ($modulos as $modulo) {
            foreach ($acciones as $accion) {
                Permission::firstOrCreate(['name' => "$accion $modulo"]);
            }
        }

        // Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $vendedor = Role::firstOrCreate(['name' => 'vendedor']);
        $vendedor->givePermissionTo([
            'ver dashboard',

            'ver ventas',
            'crear ventas',
            'editar ventas',

            'ver cotizaciones',
            'crear cotizaciones',
            'editar cotizaciones',

            'ver clientes',
            'crear clientes',

            'ver cuentas',
            'editar cuentas',

            'ver cuentasbancarias',

            'ver productos',

            'ver caja',
        ]);


        $almacenero = Role::firstOrCreate(['name' => 'almacenero']);
        $almacenero->givePermissionTo([
            'ver productos',
            'crear productos',
            'editar productos',

            'ver proveedores',
            'crear proveedores',

            'ver compras',
            'crear compras',

            'ver almacen',
            'crear almacen',
            'ver cuentasbancarias',
            'editar ventas',
        ]);
    }
}
