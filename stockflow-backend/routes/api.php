<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\StockMovementController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

// Rotas protegidas
// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

//     Route::apiResource('/products', ProductController::class);

//     Route::apiResource('/stock-movements', StockMovementController::class)
//         ->only(['index', 'store']);
//     // Route::post('/movements', [StockMovementController::class, 'store']);

//     // Route::post('/stock_movements', [StockMovementController::class, 'store']);
// });

Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

Route::apiResource('/products', ProductController::class);

Route::apiResource('/stock-movements', StockMovementController::class)
    ->only(['index', 'store']);



?>