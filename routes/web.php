<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\CategoriaServicioController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\BonoController;
use App\Http\Controllers\TipoBonoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaProductoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\TpvController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\InformesController;
use App\Http\Controllers\InformativaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [InformativaController::class, 'index']);

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Rutas protegidas
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/datos', [DashboardController::class, 'datos'])->name('dashboard.datos');

    // Configuración
    Route::middleware('admin')->prefix('configuracion')->name('configuracion.')->group(function () {
        Route::get('/', [ConfiguracionController::class, 'index'])->name('index');
        Route::post('/empresa', [ConfiguracionController::class, 'updateEmpresa'])->name('empresa.update');
        Route::post('/logo', [ConfiguracionController::class, 'updateLogo'])->name('logo.update');
        Route::post('/parametros', [ConfiguracionController::class, 'updateParametros'])->name('parametros.update');
        Route::post('/horario', [ConfiguracionController::class, 'updateHorario'])->name('horario.update');
    });

    // Usuarios (solo admin)
    Route::middleware('admin')->prefix('usuarios')->name('usuarios.')->group(function () {
        Route::get('/', [UsuarioController::class, 'index'])->name('index');
        Route::get('/crear', [UsuarioController::class, 'create'])->name('create');
        Route::post('/', [UsuarioController::class, 'store'])->name('store');
        Route::get('/{user}/editar', [UsuarioController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UsuarioController::class, 'update'])->name('update');
        Route::delete('/{user}', [UsuarioController::class, 'destroy'])->name('destroy');
    });

    // Clientes
    Route::resource('clientes', ClienteController::class);
    Route::get('/clientes/{cliente}/historial', [ClienteController::class, 'historial'])->name('clientes.historial');

    // Empleados
    Route::resource('empleados', EmpleadoController::class);

    // Servicios
    Route::resource('servicios', ServicioController::class);
    Route::resource('categorias-servicios', CategoriaServicioController::class)->except(['show']);

    // Citas - Agenda
    Route::prefix('agenda')->name('agenda.')->group(function () {
        Route::get('/', [CitaController::class, 'index'])->name('index');
        Route::get('/eventos', [CitaController::class, 'eventos'])->name('eventos');
        Route::get('/clientes', [CitaController::class, 'buscarClientes'])->name('clientes');
        Route::post('/citas', [CitaController::class, 'store'])->name('citas.store');
        Route::get('/citas/{cita}', [CitaController::class, 'show'])->name('citas.show');
        Route::put('/citas/{cita}', [CitaController::class, 'update'])->name('citas.update');
        Route::delete('/citas/{cita}', [CitaController::class, 'destroy'])->name('citas.destroy');
        Route::post('/citas/{cita}/estado', [CitaController::class, 'cambiarEstado'])->name('citas.estado');
    });

    // Bonos
    Route::resource('bonos', BonoController::class);
    Route::resource('tipos-bonos', TipoBonoController::class)->except(['show']);
    Route::post('/bonos/{bono}/usar', [BonoController::class, 'usar'])->name('bonos.usar');

    // Productos
    Route::resource('productos', ProductoController::class);
    Route::resource('categorias-productos', CategoriaProductoController::class)->except(['show']);
    Route::post('/productos/{producto}/ajuste-stock', [ProductoController::class, 'ajusteStock'])->name('productos.ajuste-stock');

    // TPV - Punto de venta
    Route::prefix('tpv')->name('tpv.')->group(function () {
        Route::get('/', [TpvController::class, 'index'])->name('index');
        Route::post('/cobrar', [TpvController::class, 'cobrar'])->name('cobrar');
        Route::get('/buscar-producto', [TpvController::class, 'buscarProducto'])->name('buscar-producto');
        Route::get('/buscar-cliente', [TpvController::class, 'buscarCliente'])->name('buscar-cliente');
    });

    // Ventas
    Route::resource('ventas', VentaController::class)->only(['index', 'show', 'destroy']);
    Route::get('/ventas/{venta}/ticket', [VentaController::class, 'ticket'])->name('ventas.ticket');

    // Caja
    Route::prefix('caja')->name('caja.')->group(function () {
        Route::get('/', [CajaController::class, 'index'])->name('index');
        Route::post('/abrir', [CajaController::class, 'abrir'])->name('abrir');
        Route::post('/cerrar', [CajaController::class, 'cerrar'])->name('cerrar');
        Route::post('/movimiento', [CajaController::class, 'movimiento'])->name('movimiento');
        Route::get('/historial', [CajaController::class, 'historial'])->name('historial');
        Route::get('/{caja}', [CajaController::class, 'show'])->name('show');
    });

    // Informes
    Route::prefix('informes')->name('informes.')->group(function () {
        Route::get('/', [InformesController::class, 'index'])->name('index');
        Route::get('/ventas', [InformesController::class, 'ventas'])->name('ventas');
        Route::get('/clientes', [InformesController::class, 'clientes'])->name('clientes');
        Route::get('/empleados', [InformesController::class, 'empleados'])->name('empleados');
        Route::get('/servicios', [InformesController::class, 'servicios'])->name('servicios');
    });

    // Backup / Restore / Reset
    Route::middleware('admin')->prefix('sistema')->name('sistema.')->group(function () {
        Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
        Route::post('/backup/crear', [BackupController::class, 'crear'])->name('backup.crear');
        Route::get('/backup/descargar/{backup}', [BackupController::class, 'descargar'])->name('backup.descargar');
        Route::post('/backup/restaurar', [BackupController::class, 'restaurar'])->name('backup.restaurar');
        Route::delete('/backup/{backup}', [BackupController::class, 'destroy'])->name('backup.destroy');
        Route::post('/backup/reset', [BackupController::class, 'reset'])->name('backup.reset');
    });
});
