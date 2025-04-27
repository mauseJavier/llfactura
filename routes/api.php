<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiPresupuestoController;
use App\Http\Middleware\CheckRole;
// app/Http/Controllers/Api/ApiPresupuestoController.php


Route::middleware(['auth:sanctum', CheckRole::class])->post('/guardarPresupuesto', [ApiPresupuestoController::class, 'store']);
// Route::middleware('auth:sanctum')->get('/presupuesto', [ApiPresupuestoController::class, 'store']);
// Route::post('/presupuesto', [ApiPresupuestoController::class, 'store']); // sin auth


// 1|PoYVVmjf6vMifsDxcEAWljBWEer4CS4o4eyBhjWC20a76ac7