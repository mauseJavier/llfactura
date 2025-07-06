<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiPresupuestoController;
use App\Http\Controllers\Api\ApiInventarioController;
use App\Http\Middleware\CheckRole;
// app/Http/Controllers/Api/ApiPresupuestoController.php




Route::middleware(['auth:sanctum', CheckRole::class])->post('/guardarPresupuesto', [ApiPresupuestoController::class, 'store']);
// Route::middleware('auth:sanctum')->get('/presupuesto', [ApiPresupuestoController::class, 'store']);
// Route::post('/presupuesto', [ApiPresupuestoController::class, 'store']); // sin auth

// Ruta pública para obtener inventarios por empresa_id (sin middleware)
Route::get('/inventarios/{empresa_id?}', [ApiInventarioController::class, 'index']);

// Ruta para buscar artículos por detalle o código (sin middleware)
Route::get('/inventarios/{empresa_id}/buscar', [ApiInventarioController::class, 'buscar']);

// Ruta para obtener los rubros únicos de una empresa
Route::get('/rubros/{empresa_id?}', [ApiInventarioController::class, 'rubros']);

// Ruta para obtener un artículo específico por empresa e id
Route::get('/inventarios/{empresa_id}/articulo/{id}', [\App\Http\Controllers\Api\ApiInventarioController::class, 'verArticulo']);

// Ruta para obtener artículos destacados con parámetros opcionales destacados y limit
Route::get('/inventarios/{empresa_id}/articulos', [\App\Http\Controllers\Api\ApiInventarioController::class, 'articulos']);

// Ruta para devolver los datos de la empresa por ID
Route::get('/empresa/{id}', [\App\Http\Controllers\Api\ApiInventarioController::class, 'empresa']);


