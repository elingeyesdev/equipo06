<?php

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
