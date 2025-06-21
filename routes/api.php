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

// 1|PoYVVmjf6vMifsDxcEAWljBWEer4CS4o4eyBhjWC20a76ac7

