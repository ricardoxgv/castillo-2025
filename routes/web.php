<?php

use Illuminate\Support\Facades\Route;
use App\Usuarios\Controllers\UserController;
use App\Usuarios\Controllers\AuthController;
use App\Ventas\Controllers\VentaController;
use App\Caja\Controllers\CajaController;
use App\Vendedores\Controllers\VendedorController;
use App\Datos\Controllers\ReporteController;
use App\Control\Controllers\ControlController;
use App\Facturacion\Controllers\FacturaController;
use App\Admin\Controllers\AdminController;
use App\Ventas\Controllers\ItemController;
use App\Ventas\Controllers\AperturaController;
use App\Ventas\Controllers\CarritoController;


// Página de bienvenida
Route::get('/', function () {
    return view('welcome');
});

// ----------------------------
// Login y Logout (sin auth)
// ----------------------------
Route::get('/login', [AuthController::class, 'show'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ----------------------------
// Rutas protegidas por login
// ----------------------------
Route::middleware('auth')->group(function () {

    // Módulo Usuarios
    Route::prefix('usuarios')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
    });

    // Módulo Ventas
    Route::prefix('ventas')->group(function () {
    Route::get('/', [VentaController::class, 'index']);
    Route::post('/', [VentaController::class, 'store']);

    //separacion de cajas entradas servicios + cochera
   Route::get('/taquilla', [VentaController::class, 'vistaTaquilla'])->name('ventas.taquilla.index');
   Route::get('/cochera', [VentaController::class, 'vistaCochera'])->name('ventas.cochera.index');



    // creacion de items
    Route::get('/items/crear', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    
    // rutasestado de items - cambio de estado
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::patch('/items/{id}/toggle', [ItemController::class, 'toggleEstado'])->name('items.toggle');
       
    //Apertura de CAJA
    Route::get('/apertura', [AperturaController::class, 'seleccionar'])->name('apertura.seleccionar');
    Route::post('/apertura', [AperturaController::class, 'aperturar'])->name('apertura.crear');

    //Rutas de TAquillas
  // Módulo Ventas
// Mostrar el carrito
    Route::get('/carrito', [CarritoController::class, 'mostrarCarrito'])
        ->name('carrito.index');

  
    // Gestión de medios de pago
    Route::get('/medios-pago', [\App\Ventas\Controllers\MedioPagoController::class, 'index'])->name('medios_pago.index');
    Route::get('/medios-pago/crear', [\App\Ventas\Controllers\MedioPagoController::class, 'create'])->name('medios_pago.create');
    Route::post('/medios-pago', [\App\Ventas\Controllers\MedioPagoController::class, 'store'])->name('medios_pago.store');

    // Gestión de empresas
    Route::get('/empresas', [\App\Ventas\Controllers\EmpresaController::class, 'index'])->name('empresas.index');
    Route::get('/empresas/crear', [\App\Ventas\Controllers\EmpresaController::class, 'create'])->name('empresas.create');
    Route::post('/empresas', [\App\Ventas\Controllers\EmpresaController::class, 'store'])->name('empresas.store');
    
    //
    // ★ Nuevas rutas para fijar selección en el carrito ★
    Route::post('/carrito/seleccionar-medio-pago', [CarritoController::class, 'seleccionarMedioPago'])
        ->name('carrito.seleccionar.medio_pago');
    Route::post('/carrito/seleccionar-empresa', [CarritoController::class, 'seleccionarEmpresa'])
        ->name('carrito.seleccionar.empresa');
//
    //carrito de items
    Route::post('/carrito/agregar/{itemId}', [CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::post('/carrito/limpiar', [CarritoController::class, 'limpiar'])->name('carrito.limpiar');
    Route::delete('/carrito/eliminar/{itemId}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar'); // ← ESTA ES LA QUE FALTABA
    Route::post('/carrito/descuento/{itemId}', [CarritoController::class, 'aplicarDescuento'])->name('carrito.descuento');
    Route::post('/carrito/descuento-global', [CarritoController::class, 'aplicarDescuentoGlobal'])->name('carrito.descuento.global');


    });

    // Módulo Caja
    Route::prefix('caja')->group(function () {
        Route::get('/', [CajaController::class, 'index']);
        Route::post('/', [CajaController::class, 'store']);
    });

    // Módulo Vendedores
    Route::prefix('vendedores')->group(function () {
        Route::get('/', [VendedorController::class, 'index']);
        Route::get('/{id}', [VendedorController::class, 'show']);
    });

    // Módulo Reportes
    Route::prefix('reportes')->group(function () {
        Route::get('/', [ReporteController::class, 'index']);
        Route::get('/{id}', [ReporteController::class, 'show']);
    });

    // Módulo Control de tickets
    Route::prefix('control')->group(function () {
        Route::get('/', [ControlController::class, 'index']);
        Route::post('/', [ControlController::class, 'store']);
    });

    // Módulo Facturación
    Route::prefix('facturacion')->group(function () {
        Route::get('/', [FacturaController::class, 'index']);
        Route::post('/', [FacturaController::class, 'store']);
    });

    // Módulo Administración
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index']);
        Route::get('/settings', [AdminController::class, 'settings']);
    });

    // Ruta home genérica después del login
    Route::get('/home', function () {
        return 'Bienvenido al sistema comercial';
    });
});
