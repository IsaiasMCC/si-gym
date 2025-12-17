<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MembresiaController;
use App\Http\Controllers\PagoFacilController;
use App\Http\Controllers\PagoReporteController;
use App\Http\Controllers\PaqueteController;
use App\Http\Controllers\PlanPagoController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RutinaController;
use App\Http\Controllers\SubscripcionController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RutinaUsuarioController;
use App\Http\Controllers\SeguimientoController;
use App\Http\Controllers\SeguimientoReporteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::delete('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

Route::get('/register', [AuthenticatedSessionController::class, 'index_create'])
    ->name('index_create')
    ->middleware('guest');

Route::post('/register', [AuthenticatedSessionController::class, 'register'])
    ->name('register')
    ->middleware('guest');

// Dashboard

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

Route::prefix('inf513/grupo18sc/proyecto2/sis-gym/public/seguimientos-reportes')->group(function () {
    Route::get('reportes', [SeguimientoReporteController::class, 'index'])->name('seguimientos.reportes');
    Route::get('reportes/export', [SeguimientoReporteController::class, 'export'])->name('seguimientos.reportes.export');
});

Route::prefix('inf513/grupo18sc/proyecto2/sis-gym/public/pagos-reportes')->group(function () {
    Route::get('/reportes', [PagoReporteController::class, 'index'])->name('pagos.reportes');
    Route::get('/reportes/export', [PagoReporteController::class, 'export'])->name('pagos.reportes.export');
});

Route::get('/inf513/grupo18sc/proyecto2/sis-gym/public/search', [DashboardController::class, 'index_search'])->name('search');
Route::resource('inf513/grupo18sc/proyecto2/sis-gym/public/roles', RoleController::class);
Route::patch('/inf513/grupo18sc/proyecto2/sis-gym/public/roles/permisos/{id}', [RoleController::class, 'updatePermissions'])->name('roles.update.permissions');
// Route::get('/inf513/grupo18sc/proyecto2/sis-gym/public/contratar-servicios', [ReservaController::class, 'catalogo'])->name('reservas.catalogo');
Route::resource('inf513/grupo18sc/proyecto2/sis-gym/public/usuarios', UsuarioController::class);

Route::post('/inf513/grupo18sc/proyecto2/sis-gym/public/pagofacil/generar-qr', [PagoFacilController::class, 'generarQR']);
Route::post('/inf513/grupo18sc/proyecto2/sis-gym/public/pagofacil/consultar-estado', [PagoFacilController::class, 'consultarEstado']);

Route::resource('inf513/grupo18sc/proyecto2/sis-gym/public/membresias', MembresiaController::class);
Route::resource('inf513/grupo18sc/proyecto2/sis-gym/public/paquetes', PaqueteController::class);
Route::resource('inf513/grupo18sc/proyecto2/sis-gym/public/rutinas', RutinaController::class);

// Subscripciones
Route::post('/inf513/grupo18sc/proyecto2/sis-gym/public/subscripciones/{membresia}/resumen', [SubscripcionController::class, 'resumen'])->name('subscripciones.resumen');
//confirmacion subscripción
Route::post('/inf513/grupo18sc/proyecto2/sis-gym/public/subscripciones/{membresia}/confirmar', [SubscripcionController::class, 'confirmar'])->name('subscripciones.confirmar');

// Planes de pago
Route::get('/inf513/grupo18sc/proyecto2/sis-gym/public/mis-subscripciones/{subscripcion}', [SubscripcionController::class, 'show'])->name('subscripciones.show2');
//Rutinas asignadas a usuarios
Route::resource('inf513/grupo18sc/proyecto2/sis-gym/public/rutinas-usuarios', RutinaUsuarioController::class);

// Subscripciones
Route::get('/inf513/grupo18sc/proyecto2/sis-gym/public/catalogos', [SubscripcionController::class, 'catalogo'])->name('subscripciones.catalogo');
Route::get('/inf513/grupo18sc/proyecto2/sis-gym/public/paquetes-adicional/{id}', [SubscripcionController::class, 'paquetes_adicional'])->name('subscripciones.paquetes_adicional');
Route::get('/inf513/grupo18sc/proyecto2/sis-gym/public/subscripciones', [SubscripcionController::class, 'index'])->name('subscripciones.index');
Route::get('/inf513/grupo18sc/proyecto2/sis-gym/public/subscripciones/create', [SubscripcionController::class, 'create'])->name('subscripciones.create');
Route::post('/subscripciones', [SubscripcionController::class, 'store'])->name('subscripciones.store');
Route::get('/inf513/grupo18sc/proyecto2/sis-gym/public/subscripciones/{subscripcion}', [SubscripcionController::class, 'show'])->name('subscripciones.show');

// Planes de pago
Route::post('/inf513/grupo18sc/proyecto2/sis-gym/public/plan-pagos/{planPago}/pagar', [PlanPagoController::class, 'pagar'])->name('plan-pagos.pagar');
// Route::resource('inf513/grupo18sc/proyecto2/sis-gym/public/seguimientos', SeguimientoController::class);

// Seguimientos

Route::get('inf513/grupo18sc/proyecto2/sis-gym/public/seguimientos/{id}', [SeguimientoController::class, 'index'])->name('seguimientos.index');
Route::get('inf513/grupo18sc/proyecto2/sis-gym/public/seguimientos/create/{id}', [SeguimientoController::class, 'create'])->name('seguimientos.create');
Route::post('inf513/grupo18sc/proyecto2/sis-gym/public/seguimientos', [SeguimientoController::class, 'store'])->name('seguimientos.store');
Route::get('inf513/grupo18sc/proyecto2/sis-gym/public/seguimientos/{seguimiento}/edit', [SeguimientoController::class, 'edit'])->name('seguimientos.edit');
Route::put('inf513/grupo18sc/proyecto2/sis-gym/public/seguimientos/{seguimiento}', [SeguimientoController::class, 'update'])->name('seguimientos.update');
Route::delete('inf513/grupo18sc/proyecto2/sis-gym/public/seguimientos/{seguimiento}', [SeguimientoController::class, 'destroy'])->name('seguimientos.destroy');

Route::get('/test-login-pagofacil', function () {
    $tokenService = "51247fae280c20410824977b0781453df59fad5b23bf2a0d14e884482f91e09078dbe5966e0b970ba696ec4caf9aa5661802935f86717c481f1670e63f35d504a62547a9de71bfc76be2c2ae01039ebcb0f74a96f0f1f56542c8b51ef7a2a6da9ea16f23e52ecc4485b69640297a5ec6a701498d2f0e1b4e7f4b7803bf5c2eba";
    $tokenSecret = "0C351C6679844041AA31AF9C";

    $response = \Illuminate\Support\Facades\Http::withHeaders([
        'tcTokenService' => $tokenService,
        'tcTokenSecret' => $tokenSecret,
        'Content-Type' => 'application/json'
    ])->post('https://masterqr.pagofacil.com.bo/api/services/v2/login');

    return response()->json([
        'status' => $response->status(),
        'body' => $response->json(),
        'headers' => $response->headers()
    ]);
});
