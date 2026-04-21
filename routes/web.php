<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EnvioController;
use App\Http\Controllers\EventoProduccionController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\ProducerController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProductorDashboardController;
use App\Http\Controllers\RecepcionController;
use App\Http\Controllers\TransportistaController;
use App\Http\Controllers\UbicacionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    return auth()->user()->esAdmin()
        ? redirect()->route('productores.index')
        : redirect()->route('productor.dashboard');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/productor', [ProductorDashboardController::class, 'index'])
        ->middleware('role:productor')
        ->name('productor.dashboard');

    Route::middleware('role:admin')->group(function () {
        Route::resource('envios', EnvioController::class)->parameters([
            'envios' => 'envio',
        ]);
        Route::post('/envios/{envio}/recepcion-conformidad', [RecepcionController::class, 'guardarConformidad'])
            ->name('envios.recepcion.conformidad');

        Route::resource('ubicaciones', UbicacionController::class)->parameters([
            'ubicaciones' => 'ubicacion',
        ]);

        Route::resource('transportistas', TransportistaController::class)->parameters([
            'transportistas' => 'transportista',
        ]);
    });

    Route::middleware('role:admin,productor')->group(function () {
        Route::resource('productores', ProducerController::class)->parameters([
            'productores' => 'producer',
        ]);

        Route::resource('productos', ProductoController::class)->parameters([
            'productos' => 'producto',
        ]);

        Route::get('/eventos-produccion', [EventoProduccionController::class, 'index'])->name('eventos-produccion.index');
        Route::get('/eventos-produccion/create', [EventoProduccionController::class, 'create'])->name('eventos-produccion.create');
        Route::post('/eventos-produccion', [EventoProduccionController::class, 'store'])->name('eventos-produccion.store');
        Route::get('/eventos-produccion/{evento_produccion}/edit', [EventoProduccionController::class, 'edit'])->name('eventos-produccion.edit');
        Route::put('/eventos-produccion/{evento_produccion}', [EventoProduccionController::class, 'update'])->name('eventos-produccion.update');
        Route::post('/eventos-produccion/{evento_produccion}/completar', [EventoProduccionController::class, 'completar'])->name('eventos-produccion.completar');
        Route::delete('/eventos-produccion/{evento_produccion}', [EventoProduccionController::class, 'destroy'])->name('eventos-produccion.destroy');

        Route::get('/lotes', [LoteController::class, 'index'])->name('lotes.index');
        Route::get('/lotes/create', [LoteController::class, 'create'])->name('lotes.create');
        Route::post('/lotes', [LoteController::class, 'store'])->name('lotes.store');
        Route::get('/lotes/{lote}/edit', [LoteController::class, 'edit'])->name('lotes.edit');
        Route::put('/lotes/{lote}', [LoteController::class, 'update'])->name('lotes.update');
        Route::delete('/lotes/{lote}', [LoteController::class, 'destroy'])->name('lotes.destroy');
        Route::get('/lotes/{lote}', [LoteController::class, 'show'])->name('lotes.show');
    });
});
