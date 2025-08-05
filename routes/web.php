<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/firebase-setup', function () {
    if (!app()->environment('local')) {
        abort(404);
    }
    return view('auth.firebase-setup');
})->name('firebase.setup');


Route::get('/api/docs', [App\Http\Controllers\ApiDocumentationController::class, 'index'])->name('api.docs');
Route::get('/api/openapi.json', [App\Http\Controllers\ApiDocumentationController::class, 'openapi'])->name('api.openapi');


Route::get('/health', [App\Http\Controllers\HealthController::class, 'basic'])->name('health.basic');
Route::get('/health/detailed', [App\Http\Controllers\HealthController::class, 'detailed'])->name('health.detailed');
Route::get('/health/ready', [App\Http\Controllers\HealthController::class, 'ready'])->name('health.ready');
Route::get('/health/live', [App\Http\Controllers\HealthController::class, 'live'])->name('health.live');
Route::get('/metrics', [App\Http\Controllers\HealthController::class, 'metrics'])->name('metrics');
Route::get('/status', [App\Http\Controllers\HealthController::class, 'status'])->name('status');
Route::get('/performance', [App\Http\Controllers\HealthController::class, 'performance'])->name('performance');
Route::get('/config', [App\Http\Controllers\HealthController::class, 'config'])->name('config');


Route::group([], function () {
    

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    

    Route::get('/productos', [ProductController::class, 'index'])->middleware('throttle:search')->name('products.index');
    Route::get('/productos/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::post('/productos/{product}/canjear', [ProductController::class, 'redeem'])->middleware('throttle:redemption')->name('products.redeem');
    

    Route::get('/favoritos', [FavoriteController::class, 'index'])->middleware('firebase.auth')->name('favorites.index');
    Route::post('/favoritos/{product}', [FavoriteController::class, 'toggle'])->middleware('throttle:favorites')->name('favorites.toggle');
    Route::delete('/favoritos/{favorite}', [FavoriteController::class, 'destroy'])->middleware('throttle:favorites')->name('favorites.destroy');
    

    Route::get('/pedidos', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/pedidos/{order}', [OrderController::class, 'show'])->name('orders.show');
    

    Route::get('/perfil', [AuthController::class, 'profile'])->name('profile');
    Route::put('/perfil', [AuthController::class, 'updateProfile'])->middleware('throttle:profile')->name('profile.update');
    

    Route::middleware(['require.admin', 'throttle:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/pedidos', [AdminController::class, 'orders'])->name('orders');
        Route::put('/pedidos/{order}/estado', [AdminController::class, 'updateOrderStatus'])->name('orders.update-status');
        Route::get('/empleados', [AdminController::class, 'employees'])->name('employees');
        Route::get('/productos', [AdminController::class, 'products'])->name('products');
        Route::get('/reportes', [AdminController::class, 'reports'])->name('reports');
    });
});

