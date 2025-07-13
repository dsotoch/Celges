<?php

use App\Http\Controllers\ControllerAlmacenInterno;
use App\Http\Controllers\ControllerCaja;
use App\Http\Controllers\ControllerCompra;
use App\Http\Controllers\ControllerCotizaciones;
use App\Http\Controllers\ControllerCuentaBancaria;
use App\Http\Controllers\ControllerCuentas;
use App\Http\Controllers\ControllerPagos;
use App\Http\Controllers\ControllerPersona;
use App\Http\Controllers\ControllerProducto;
use App\Http\Controllers\ControllerReportes;
use App\Http\Controllers\ControllerVentas;
use Illuminate\Support\Facades\Route;

Route::get('/', [ControllerReportes::class, 'index']);
Route::prefix('productos')->controller(ControllerProducto::class)->group(function () {
    Route::get('/', 'index')->name('productos.index');
    Route::post('/guardar', 'store')->name('productos.store');
    Route::get('/{id}', 'show')->name('productos.show');
    Route::put('/edit/{id}', 'update')->name('productos.update');
    Route::delete('/{id}', 'destroy')->name('productos.destroy');
});
Route::prefix('proveedores')
    ->controller(ControllerPersona::class)
    ->group(function () {
        Route::get('/',             'index')->name('proveedores.index');
        Route::post('/guardar',     'store')->name('proveedores.store');
        Route::post('/guardarcliente/{id}', 'create')->name('proveedores.create');
        Route::get('/{id}',         'show')->name('proveedores.show');
        Route::put('/edit/{id}',    'update')->name('proveedores.update');
        Route::delete('/{id}',      'destroy')->name('proveedores.destroy');
    });
Route::prefix('compras')
    ->controller(ControllerCompra::class)
    ->group(function () {
        Route::get('/',             'index')->name('compras.index');         // Mostrar lista de compras
        Route::get('/saldo-favor/{id}',             'ListarSaldoFavorCliente')->name('compras.saldoFavor');         // Mostrar lista de compras
        Route::post('/guardar',     'store')->name('compras.store');         // Guardar nueva compra
        Route::get('/{id}',         'show')->name('compras.show');           // Ver detalle de una compra
        Route::put('/edit/{id}',    'update')->name('compras.update');       // Actualizar compra
        Route::delete('/{id}',      'destroy')->name('compras.destroy');     // Eliminar compra
    });
Route::prefix('almaceninterno')
    ->controller(ControllerAlmacenInterno::class)
    ->group(function () {
        Route::get('/',             'index')->name('almaceninterno.index');        // Mostrar lista
        Route::post('/guardar',     'store')->name('almaceninterno.store');        // Guardar nuevo registro
        Route::get('/{id}',         'show')->name('almaceninterno.show');          // Ver detalle
        Route::get('/productos/{id}', 'listarProductosPorId')->name("almaceninterno.productoporid");
        Route::delete('/{id}',      'destroy')->name('almaceninterno.destroy');    // Eliminar
    });
Route::prefix('ventas')
    ->controller(ControllerVentas::class)
    ->group(function () {
        Route::get('/',             'index')->name('ventas.index');        // Mostrar lista
        Route::post('/guardar',     'store')->name('ventas.store');        // Guardar nuevo registro
        Route::post('/crear',     'create')->name('ventas.create');        // Guardar nuevo registro
        Route::get('/{id}',         'show')->name('ventas.show');          // Ver detalle
        Route::put('/editar/{id}',   'update')->name('ventas.update');    // Editar
        Route::put('/anular/{id}',   'anular')->name('ventas.anular');    // Anular Venta
        Route::put('/modificarVenta',   'actualizarVentayProductos')->name('ventas.actualizarVentaYProducto');    // ActualizarVentayProductos
        Route::delete('/{id}',      'destroy')->name('ventas.destroy');    // Eliminar
    });
Route::prefix('cotizacion')
    ->controller(ControllerCotizaciones::class)
    ->group(function () {
        Route::get('/',             'index')->name('cotizacion.index');        // Mostrar lista
        Route::post('/guardar',     'store')->name('cotizacion.store');        // Guardar nuevo registro
        Route::get('/{id}',         'show')->name('cotizacion.show');          // Ver detalle
        Route::put('/editar/{id}',      'update')->name('cotizacion.update');    // Eliminar

    });
Route::prefix('caja')
    ->controller(ControllerCaja::class)
    ->group(function () {
        Route::get('/',             'index')->name('caja.index');        // Mostrar lista
        Route::post('/guardar',     'store')->name('caja.store');        // Guardar nuevo registro
        Route::get('/{id}',         'show')->name('caja.show');          // Ver detalle
        Route::put('/editar/{id}',      'update')->name('caja.update');    // Eliminar

    });
Route::prefix('cuentasbancarias')
    ->controller(ControllerCuentaBancaria::class)
    ->group(function () {
        Route::get('/',             'index')->name('cuentasbancarias.index');        // Mostrar lista
        Route::post('/guardar',     'store')->name('cuentasbancarias.store');        // Guardar nuevo registro
        Route::put('/editar/{id}', 'update')->name('cuentasbancarias.update');
        Route::delete('/delete/{id}', 'destroy')->name('cuentasbancarias.destroy');    // Eliminar
    });
Route::prefix('pagos')
    ->controller(ControllerPagos::class)
    ->group(function () {
        Route::get('/',             'index')->name('pagos.index');        // Mostrar lista
        Route::post('/guardar',     'store')->name('pagos.store');        // Guardar nuevo registro
        Route::post('/guardar-pago-compra',  'createPagoCompra')->name('pagos.create');        // Guardar nuevo registro
        Route::post('/guardar-pago-servicio',  'createPagoServicio')->name('pagos.crearservicio');        // Guardar nuevo registro
        Route::put('/editar/{id}', 'update')->name('pagos.update');
        Route::delete('/delete/{id}', 'destroy')->name('pagos.destroy');    // Eliminar
    });
Route::prefix('cuentas')
    ->controller(ControllerCuentas::class)
    ->group(function () {
        Route::get('/',             'index')->name('cuentas.index');        // Mostrar lista
        Route::get('/{id}',     'show')->name('cuentas.show');        // Guardar nuevo registro
        Route::get('/saldoPendiente/{id}', 'saldoPendienteCuentaCliente');
        Route::post('/guardar',     'store')->name('cuentas.store');        // Guardar nuevo registro
        Route::put('/editar/{id}', 'update')->name('cuentas.update');
        Route::delete('/delete/{id}', 'destroy')->name('cuentas.destroy');    // Eliminar

    });
