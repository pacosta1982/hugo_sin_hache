<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Product;
use App\Models\Order;
use App\Services\CacheService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PointsService
{
    public function redeemProduct(Employee $employee, Product $product, array $additionalData = []): array
    {
        return DB::transaction(function () use ($employee, $product, $additionalData) {
            if (!$employee->hasEnoughPoints($product->costo_puntos)) {
                throw new \Exception('Puntos insuficientes para canjear este producto.');
            }

            if (!$product->is_available) {
                throw new \Exception('El producto no está disponible para canje.');
            }

            if (!$product->hasStock()) {
                throw new \Exception('No hay stock disponible para este producto.');
            }

            $order = Order::create([
                'empleado_id' => $employee->id_empleado,
                'producto_id' => $product->id,
                'fecha' => now(),
                'estado' => Order::STATUS_PENDING,
                'puntos_utilizados' => $product->costo_puntos,
                'producto_nombre' => $product->nombre,
                'empleado_nombre' => $employee->nombre,
                'observaciones' => $additionalData['observaciones'] ?? null,
            ]);

            $employee->deductPoints($product->costo_puntos);

            $product->decrementStock();


            $cacheService = app(CacheService::class);
            $cacheService->clearEmployeeCache($employee->id_empleado);
            $cacheService->clearAdminCache();

            Log::info('Product redeemed successfully', [
                'employee_id' => $employee->id_empleado,
                'product_id' => $product->id,
                'order_id' => $order->id,
                'points_used' => $product->costo_puntos,
            ]);

            return [
                'success' => true,
                'order' => $order,
                'remaining_points' => $employee->fresh()->puntos_totales,
                'message' => 'Producto canjeado exitosamente. Tu pedido está siendo procesado.',
            ];
        });
    }

    public function cancelOrder(Order $order, string $reason = null): array
    {
        return DB::transaction(function () use ($order, $reason) {
            if (!$order->can_be_cancelled) {
                throw new \Exception('Este pedido no puede ser cancelado en su estado actual.');
            }

            $employee = $order->employee;
            $product = $order->product;


            $employee->update([
                'puntos_totales' => $employee->puntos_totales + $order->puntos_utilizados,
                'puntos_canjeados' => $employee->puntos_canjeados - $order->puntos_utilizados,
            ]);


            if ($product) {
                $product->incrementStock();
            }


            $order->update([
                'estado' => Order::STATUS_CANCELLED,
                'observaciones' => $reason ? "Cancelado: {$reason}" : 'Pedido cancelado',
            ]);


            $cacheService = app(CacheService::class);
            $cacheService->clearEmployeeCache($employee->id_empleado);
            $cacheService->clearAdminCache();

            Log::info('Order cancelled and points refunded', [
                'order_id' => $order->id,
                'employee_id' => $employee->id_empleado,
                'points_refunded' => $order->puntos_utilizados,
                'reason' => $reason,
            ]);

            return [
                'success' => true,
                'order' => $order->fresh(),
                'refunded_points' => $order->puntos_utilizados,
                'message' => 'Pedido cancelado exitosamente. Tus puntos han sido restaurados.',
            ];
        });
    }

    public function updateOrderStatus(Order $order, string $newStatus, string $notes = null): array
    {
        $validStatuses = Order::availableStatuses();
        
        if (!in_array($newStatus, $validStatuses)) {
            throw new \Exception('Estado de pedido inválido.');
        }

        $oldStatus = $order->estado;
        
        $order->update([
            'estado' => $newStatus,
            'observaciones' => $notes ?: $order->observaciones,
        ]);


        $cacheService = app(CacheService::class);
        $cacheService->clearEmployeeCache($order->empleado_id);
        $cacheService->clearAdminCache();

        Log::info('Order status updated', [
            'order_id' => $order->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'notes' => $notes,
        ]);

        return [
            'success' => true,
            'order' => $order->fresh(),
            'message' => "Estado del pedido actualizado a: {$newStatus}",
        ];
    }

    public function getPointsSummary(Employee $employee): array
    {
        $totalOrders = Order::where('empleado_id', $employee->id_empleado)->count();
        $pendingOrders = Order::where('empleado_id', $employee->id_empleado)
            ->where('estado', Order::STATUS_PENDING)
            ->count();
        $completedOrders = Order::where('empleado_id', $employee->id_empleado)
            ->where('estado', Order::STATUS_COMPLETED)
            ->count();

        return [
            'available_points' => $employee->puntos_totales,
            'redeemed_points' => $employee->puntos_canjeados,
            'total_points_earned' => $employee->puntos_totales + $employee->puntos_canjeados,
            'total_orders' => $totalOrders,
            'pending_orders' => $pendingOrders,
            'completed_orders' => $completedOrders,
        ];
    }

    public function canAffordProduct(Employee $employee, Product $product): bool
    {
        return $employee->hasEnoughPoints($product->costo_puntos);
    }

    public function getRecommendedProducts(Employee $employee, int $limit = 6): \Illuminate\Database\Eloquent\Collection
    {
        return Product::active()
            ->available()
            ->where('costo_puntos', '<=', $employee->puntos_totales)
            ->orderBy('costo_puntos', 'desc')
            ->limit($limit)
            ->get();
    }
}