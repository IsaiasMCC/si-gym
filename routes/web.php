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

Route::match(['get', 'head'], '/', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');


Route::prefix('/seguimientos-reportes')->group(function () {
    Route::get('reportes', [SeguimientoReporteController::class, 'index'])->name('seguimientos.reportes');
    Route::get('reportes/export', [SeguimientoReporteController::class, 'export'])->name('seguimientos.reportes.export');
});

Route::prefix('/pagos-reportes')->group(function () {
    Route::get('/reportes', [PagoReporteController::class, 'index'])->name('pagos.reportes');
    Route::get('/reportes/export', [PagoReporteController::class, 'export'])->name('pagos.reportes.export');
});

Route::get('/search', [DashboardController::class, 'index_search'])->name('search');
Route::resource('roles', RoleController::class);
Route::patch('/roles/permisos/{id}', [RoleController::class, 'updatePermissions'])->name('roles.update.permissions');
// Route::get('/contratar-servicios', [ReservaController::class, 'catalogo'])->name('reservas.catalogo');
Route::resource('usuarios', UsuarioController::class);

Route::post('/pagofacil/generar-qr', [PagoFacilController::class, 'generarQR'])->name('pagofacil.generarQR');
Route::post('/pagofacil/consultar-estado', [PagoFacilController::class, 'consultarEstado'])->name('pagofacil.consultarEstado');

Route::resource('membresias', MembresiaController::class);
Route::resource('paquetes', PaqueteController::class);
Route::resource('rutinas', RutinaController::class);

// Subscripciones
Route::post('/subscripciones/{membresia}/resumen', [SubscripcionController::class, 'resumen'])->name('subscripciones.resumen');
//confirmacion subscripción
Route::post('/subscripciones/{membresia}/confirmar', [SubscripcionController::class, 'confirmar'])->name('subscripciones.confirmar');

// Planes de pago
Route::get('/mis-subscripciones/{subscripcion}', [SubscripcionController::class, 'show'])->name('subscripciones.show2');
//Rutinas asignadas a usuarios
Route::resource('rutinas-usuarios', RutinaUsuarioController::class);

// Subscripciones
Route::get('/catalogos', [SubscripcionController::class, 'catalogo'])->name('subscripciones.catalogo');
Route::get('/paquetes-adicional/{id}', [SubscripcionController::class, 'paquetes_adicional'])->name('subscripciones.paquetes_adicional');
Route::get('/subscripciones', [SubscripcionController::class, 'index'])->name('subscripciones.index');
Route::get('/subscripciones/create', [SubscripcionController::class, 'create'])->name('subscripciones.create');
Route::post('/subscripciones', [SubscripcionController::class, 'store'])->name('subscripciones.store');
Route::get('/subscripciones/{subscripcion}', [SubscripcionController::class, 'show'])->name('subscripciones.show');

// Planes de pago
Route::post('/plan-pagos/{planPago}/pagar', [PlanPagoController::class, 'pagar'])->name('plan-pagos.pagar');
// Route::resource('seguimientos', SeguimientoController::class);

// Seguimientos

Route::get('/seguimientos/{id}', [SeguimientoController::class, 'index'])->name('seguimientos.index');
Route::get('/seguimientos/create/{id}', [SeguimientoController::class, 'create'])->name('seguimientos.create');
Route::post('/seguimientos', [SeguimientoController::class, 'store'])->name('seguimientos.store');
Route::get('/seguimientos/{seguimiento}/edit', [SeguimientoController::class, 'edit'])->name('seguimientos.edit');
Route::put('/seguimientos/{seguimiento}', [SeguimientoController::class, 'update'])->name('seguimientos.update');
Route::delete('/seguimientos/{seguimiento}', [SeguimientoController::class, 'destroy'])->name('seguimientos.destroy');

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
