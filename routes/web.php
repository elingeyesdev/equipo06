<?php

use App\Http\Controllers\EventoProduccionController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProducerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProducerController::class, 'index']);

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
