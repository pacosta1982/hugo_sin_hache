<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HealthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('health')->group(function () {
    Route::get('/', [HealthController::class, 'basic']);
    Route::get('/detailed', [HealthController::class, 'detailed']);
    Route::get('/metrics', [HealthController::class, 'metrics']);
    Route::get('/status', [HealthController::class, 'status']);
    Route::get('/performance', [HealthController::class, 'performance']);
    Route::get('/ready', [HealthController::class, 'ready']);
    Route::get('/live', [HealthController::class, 'live']);
    Route::get('/config', [HealthController::class, 'config']);
});



Route::middleware('firebase.auth')->group(function () {
    

    Route::get('/me', [AuthController::class, 'currentUser']);
    

    Route::post('/productos/{product}/canjear', [ProductController::class, 'redeem'])->middleware('throttle:redemption');
    

    Route::get('/pedidos', [OrderController::class, 'apiHistory']);
    Route::get('/pedidos/{order}', [OrderController::class, 'apiShow']);
    Route::post('/pedidos/{order}/cancel', [OrderController::class, 'cancel']);
    

    Route::get('/favoritos', [FavoriteController::class, 'apiIndex']);
    Route::post('/favoritos/{product}', [FavoriteController::class, 'toggle'])->middleware('throttle:favorites');
    Route::delete('/favoritos/{favorite}', [FavoriteController::class, 'destroy'])->middleware('throttle:favorites');
    

    Route::put('/perfil', [AuthController::class, 'updateProfile'])->middleware('throttle:profile');
    

    Route::middleware(['require.admin', 'throttle:admin'])->prefix('admin')->group(function () {
        Route::get('/pedidos', [AdminController::class, 'apiOrders']);
        Route::put('/pedidos/{order}/estado', [AdminController::class, 'updateOrderStatus']);
        Route::get('/empleados', [AdminController::class, 'apiEmployees']);
        Route::get('/productos', [AdminController::class, 'apiProducts']);
        Route::get('/reportes', [AdminController::class, 'apiReports']);
    });
});