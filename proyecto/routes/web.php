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
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view("login");
})->name("login");
Route::get('/home', [ControllerReportes::class, 'index'])->name("dashboard.admin")->middleware("auth");
Route::get('/reportes', [ControllerReportes::class, 'reportes'])->name("dashboard.reportes")->middleware("auth");
Route::post('/reportes-cliente', [ControllerReportes::class, 'reportescliente'])->name("reportes.generar")->middleware("auth");
Route::post('/reportes-proveedor', [ControllerReportes::class, 'reportesProveedor'])->name("reportes.generarpro")->middleware("auth");

Route::post('/configuraciones', [ControllerCaja::class, 'createConfiguraciones'])->middleware("auth");

Route::prefix('usuarios')->controller(UsuarioController::class)->group(function () {
    Route::get('/', 'index')->name('usuarios.index');
    Route::post('/guardar', 'store')->name('usuarios.store');
    Route::post('/login', 'login')->name('usuarios.login');
    Route::get('/logout', 'logout')->name('usuarios.logout');
    Route::put('/edit/{id}', 'update')->name('usuarios.updatePermisos');
    Route::delete('/eliminar/{id}', 'destroy')->name('usuarios.destroy');
})->middleware("auth");
Route::prefix('productos')->controller(ControllerProducto::class)->group(function () {
    Route::get('/', 'index')->name('productos.index');
    Route::post('/guardar', 'store')->name('productos.store')->middleware(['permission:crear productos']);
    Route::post('/guardar-marca', 'guardarMarca')->name('productos.marca')->middleware(['permission:crear productos']);
    Route::post('/guardar-capacidad', 'guardarCapacidad')->name('productos.capacidad')->middleware(['permission:crear productos']);

    Route::get('/{id}', 'show')->name('productos.show');
    Route::put('/edit/{id}', 'update')->name('productos.update')->middleware(['permission:editar productos']);
    Route::delete('/{id}', 'destroy')->name('productos.destroy')->middleware(['permission:eliminar productos']);
})->middleware("auth");
Route::prefix('proveedores')
    ->controller(ControllerPersona::class)
    ->group(function () {
        Route::get('/',             'index')->name('proveedores.index');
        Route::post('/guardar',     'store')->name('proveedores.store');
        Route::post('/guardarcliente/{id}', 'create')->name('proveedores.create');
        Route::get('/{id}',         'show')->name('proveedores.show');
        Route::put('/edit/{id}',    'update')->name('proveedores.update');
        Route::delete('/{id}',      'destroy')->name('proveedores.destroy');
    })->middleware("auth");
Route::get('/tipoClientes', [ControllerPersona::class, 'clientes'])->name('proveedores.tipoclientes');

Route::prefix('compras')
    ->controller(ControllerCompra::class)
    ->group(function () {
        Route::get('/',             'index')->name('compras.index');         // Mostrar lista de compras
        Route::get('/saldo-favor/{id}',             'ListarSaldoFavorCliente')->name('compras.saldoFavor');         // Mostrar lista de compras
        Route::post('/guardar',     'store')->name('compras.store')->middleware(['permission:crear compras']);         // Guardar nueva compra
        Route::get('/buscar/{imei}', 'buscarPorImei')->name('compras.buscar');
        Route::get('/{id}',         'show')->name('compras.show')->middleware(['permission:ver compras']);           // Ver detalle de una compra
        Route::put('/edit/{id}',    'update')->name('compras.update')->middleware(['permission:editar compras']);      // Actualizar compra
        Route::delete('/{id}',      'destroy')->name('compras.destroy')->middleware(['permission:editar compras']);     // Eliminar compra
        Route::put('/modificar-detalle/{id}',    'updatedetalle')->name('compras.updatedetalle')->middleware(['permission:editar compras']);      // Actualizar compra
        Route::put('/modificar-compra',    'updatecolor')->name('compras.updatecolor')   ;   // Actualizar compra

    })->middleware("auth");
Route::prefix('almaceninterno')
    ->controller(ControllerAlmacenInterno::class)
    ->group(function () {
        Route::get('/',             'index')->name('almaceninterno.index');        // Mostrar lista
        Route::post('/guardar',     'store')->name('almaceninterno.store');        // Guardar nuevo registro
        Route::get('/{id}',         'show')->name('almaceninterno.show');          // Ver detalle
        Route::get('/productos/{id}', 'listarProductosPorId')->name("almaceninterno.productoporid");
        Route::delete('/{id}',      'destroy')->name('almaceninterno.destroy');    // Eliminar
        Route::put('/modificar-precios',      'modificarPrecio')->name('almaceninterno.update');    // Eliminar
        Route::get('/almacen/pdf', 'exportarPDF')->name('almaceninterno.pdf');
    })->middleware("auth");

Route::prefix('ventas')
    ->controller(ControllerVentas::class)
    ->group(function () {
        Route::get('/todas',         'index')->name('ventas.index');
        Route::get('/generar-pdf/{id}',         'pdf')->name('ventas.pdf');

        Route::get('/eliminar-producto/{venta}',         'eliminarproducto')->name('ventas.eliminar');

        Route::get('/actualizar-precios-producto/{venta}/{id}/{cantidad}',         'actualizarpreciosProductos')->name('ventas.actualizarprecios');
        Route::get('/actualizar-precios-productoinput/{venta}/{input}/{cantidad}',         'actualizarprecioporinput')->name('ventas.actualizarprecioporinput');

        // Route::get('/',             'index')->name('ventas.index');        // Mostrar lista
        Route::post('/guardar',     'store')->name('ventas.store')->middleware(['permission:crear ventas']);      // Guardar nuevo registro
        Route::post('/crear',     'create')->name('ventas.create')->middleware(['permission:crear ventas']);     // Guardar nuevo registro
        Route::get('/{id}',         'show')->name('ventas.show');          // Ver detalle
        Route::put('/editar/{id}',   'update')->name('ventas.update')->middleware(['permission:editar ventas']);    // Editar
        Route::put('/anular/{id}',   'anular')->name('ventas.anular')->middleware(['permission:editar ventas']);    // Anular Venta
        Route::put('/modificarVenta',   'actualizarVentayProductos')->name('ventas.actualizarVentaYProducto');    // ActualizarVentayProductos
        Route::delete('/{id}',      'destroy')->name('ventas.destroy')->middleware(['permission:eliminar ventas']);    // Eliminar
    })->middleware("auth");
Route::prefix('cotizacion')
    ->controller(ControllerCotizaciones::class)
    ->group(function () {
        Route::get('/',             'index')->name('cotizacion.index');        // Mostrar lista
        Route::post('/guardar',     'store')->name('cotizacion.store');        // Guardar nuevo registro
        Route::get('/{id}',         'show')->name('cotizacion.show');          // Ver detalle
        Route::put('/editar/{id}',      'update')->name('cotizacion.update');    // Eliminar

    })->middleware("auth");
Route::prefix('caja')
    ->controller(ControllerCaja::class)
    ->group(function () {
        Route::get('/',             'index')->name('caja.index');        // Mostrar lista
        Route::post('/guardar',     'store')->name('caja.store');        // Guardar nuevo registro
        Route::get('/{id}',         'show')->name('caja.show');          // Ver detalle
        Route::put('/editar/{id}',      'update')->name('caja.update');    // Eliminar
        Route::post('/cerrar',             'cerrar')->name('caja.cerrar');
    })->middleware("auth");
Route::prefix('cuentasbancarias')
    ->controller(ControllerCuentaBancaria::class)
    ->group(function () {
        Route::get('/',             'index')->name('cuentasbancarias.index');        // Mostrar lista
        Route::post('/guardar',     'store')->name('cuentasbancarias.store')->middleware(['permission:crear cuentasbancarias']);        // Guardar nuevo registro
        Route::put('/editar/{id}', 'update')->name('cuentasbancarias.update')->middleware(['permission:editar cuentasbancarias']);
        Route::delete('/delete/{id}', 'destroy')->name('cuentasbancarias.destroy')->middleware(['permission:eliminar cuentasbancarias']);   // Eliminar
        Route::get('/pdf/{inicio}/{fin}',             'pdf')->name('cuentasbancarias.pdf')->middleware("auth");        // Mostrar lista

    })->middleware("auth");
Route::prefix('pagos')
    ->controller(ControllerPagos::class)
    ->group(function () {
        Route::get('/',             'index')->name('pagos.index');
        Route::get('/validar-pago-view', 'validar')->name('pagos.validarindex');        // Mostrar lista
        Route::post('/guardar',     'store')->name('pagos.store');        // Guardar nuevo registro
        Route::post('/guardar-pago-compra',  'createPagoCompra')->name('pagos.create');        // Guardar nuevo registro
        Route::post('/guardar-pago-servicio',  'createPagoServicio')->name('pagos.crearservicio');        // Guardar nuevo registro
        Route::put('/editar/{id}', 'update')->name('pagos.update');
        Route::delete('/delete/{id}', 'destroy')->name('pagos.destroy');    // Eliminar
        Route::put('/validar-pago',  'validarPago')->name('pagos.validar');        // Guardar nuevo registro

    })->middleware("auth");
Route::prefix('cuentas')
    ->controller(ControllerCuentas::class)
    ->group(function () {
        Route::get('/',             'index')->name('cuentas.index');        // Mostrar lista
        Route::get('/{id}',     'show')->name('cuentas.show');        // Guardar nuevo registro
        Route::get('/saldoPendiente/{id}', 'saldoPendienteCuentaCliente');
        Route::post('/guardar',     'store')->name('cuentas.store');        // Guardar nuevo registro
        Route::put('/editar/{id}', 'update')->name('cuentas.update');
        Route::delete('/delete/{id}', 'destroy')->name('cuentas.destroy');    // Eliminar

    })->middleware("auth");
