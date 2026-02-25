<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\StockMovementController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

// Rotas protegidas ainda nao ta funcionando por algum motivo
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

Route::get('/reports', [ReportController::class, 'index']);

Route::apiResource('/categories', CategoryController::class);
// Route::get('categories', function () {
//     return 'funcionou';
// });
Route::get('teste', function () {
    return 'ok';
});


?>