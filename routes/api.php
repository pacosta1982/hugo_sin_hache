<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\Api\PointsController;

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
    
    // User notifications
    Route::get('/notificaciones', [AuthController::class, 'notifications']);
    
    // Product recommendations
    Route::get('/recommendations', [ProductController::class, 'getRecommendations']);
    
    // Product categories
    Route::get('/categories', [ProductController::class, 'getCategories']);
    Route::get('/categories/{category}/recommendations', [ProductController::class, 'getCategoryRecommendations']);
    
    // PWA Push notifications
    Route::post('/push-subscription', function (Request $request) {
        // Store push subscription for the authenticated user
        $employee = $request->attributes->get('employee');
        if (!$employee) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $subscription = $request->validate([
            'subscription' => 'required|array',
            'subscription.endpoint' => 'required|url',
            'subscription.keys' => 'required|array',
            'subscription.keys.p256dh' => 'required|string',
            'subscription.keys.auth' => 'required|string',
        ]);
        
        // Here you would store the subscription in your database
        // For now, we'll just return success
        \Illuminate\Support\Facades\Log::info('Push subscription received', [
            'employee_id' => $employee->id_empleado,
            'endpoint' => $subscription['subscription']['endpoint']
        ]);
        
        return response()->json(['success' => true]);
    })->middleware('throttle:api');
    

    Route::middleware(['require.admin', 'throttle:admin'])->prefix('admin')->group(function () {
        Route::get('/pedidos', [AdminController::class, 'apiOrders']);
        Route::put('/pedidos/{order}/estado', [AdminController::class, 'updateOrderStatus']);
        Route::get('/empleados', [AdminController::class, 'apiEmployees']);
        Route::get('/productos', [AdminController::class, 'apiProducts']);
        Route::get('/reportes', [AdminController::class, 'apiReports']);
    });
});


Route::prefix('points')->middleware(['throttle:api'])->group(function () {
    Route::post('/award', [PointsController::class, 'awardPoints']);
    Route::post('/bulk-award', [PointsController::class, 'bulkAwardPoints']);
    Route::get('/employee/{employeeId}', [PointsController::class, 'getEmployeePoints']);
    Route::get('/employee/{employeeId}/transactions', [PointsController::class, 'getTransactionHistory']);
});