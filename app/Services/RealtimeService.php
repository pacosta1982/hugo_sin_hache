<?php

namespace App\Services;

use App\Events\OrderStatusUpdated;
use App\Events\PointsAwarded;
use App\Events\LowStockAlert;
use App\Events\NewOrderCreated;
use App\Models\Order;
use App\Models\Employee;
use App\Models\Product;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class RealtimeService
{
    public function broadcastOrderStatusUpdate(Order $order, string $previousStatus): void
    {
        try {
            Event::dispatch(new OrderStatusUpdated($order, $previousStatus));
            
            Log::info('Order status update broadcasted', [
                'order_id' => $order->id,
                'new_status' => $order->estado,
                'previous_status' => $previousStatus,
                'employee_id' => $order->employee->id_empleado,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to broadcast order status update', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function broadcastPointsAwarded(Employee $employee, int $points, string $description, ?string $awardedBy = null): void
    {
        try {
            Event::dispatch(new PointsAwarded($employee, $points, $description, $awardedBy));
            
            Log::info('Points awarded broadcasted', [
                'employee_id' => $employee->id_empleado,
                'points' => $points,
                'new_balance' => $employee->puntos_totales,
                'awarded_by' => $awardedBy,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to broadcast points awarded', [
                'employee_id' => $employee->id_empleado,
                'points' => $points,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function broadcastLowStockAlert(Product $product, string $alertType = 'low_stock'): void
    {
        try {
            Event::dispatch(new LowStockAlert($product, $alertType));
            
            Log::info('Low stock alert broadcasted', [
                'product_id' => $product->id,
                'product_name' => $product->nombre,
                'current_stock' => $product->stock,
                'alert_type' => $alertType,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to broadcast low stock alert', [
                'product_id' => $product->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function broadcastNewOrderCreated(Order $order): void
    {
        try {
            Event::dispatch(new NewOrderCreated($order));
            
            Log::info('New order created broadcasted', [
                'order_id' => $order->id,
                'employee_id' => $order->employee->id_empleado,
                'product_id' => $order->product->id,
                'points_used' => $order->puntos_utilizados,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to broadcast new order created', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getRealtimeConfig(): array
    {
        return [
            'enabled' => config('broadcasting.default') !== 'null',
            'driver' => config('broadcasting.default'),
            'pusher' => [
                'app_id' => config('broadcasting.connections.pusher.app_id'),
                'key' => config('broadcasting.connections.pusher.key'),
                'secret' => config('broadcasting.connections.pusher.secret'),
                'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                'encrypted' => config('broadcasting.connections.pusher.options.encrypted', true),
            ],
            'channels' => [
                'admin' => [
                    'orders' => 'admin.orders',
                    'inventory' => 'admin.inventory',
                    'points' => 'admin.points',
                ],
                'employee' => 'employee.{employee_id}',
            ],
            'fallback_polling_interval' => 30000,
        ];
    }

    public function getEmployeeChannelName(string $employeeId): string
    {
        return "employee.{$employeeId}";
    }

    public function getAdminChannelName(string $type): string
    {
        $validTypes = ['orders', 'inventory', 'points'];
        
        if (!in_array($type, $validTypes)) {
            throw new \InvalidArgumentException("Invalid admin channel type: {$type}");
        }
        
        return "admin.{$type}";
    }

    public function testRealtimeConnection(): array
    {
        try {
            $config = $this->getRealtimeConfig();
            
            if (!$config['enabled']) {
                return [
                    'success' => false,
                    'message' => 'Real-time updates are not configured',
                    'config' => $config
                ];
            }


            $testData = [
                'test' => true,
                'timestamp' => now()->toISOString(),
                'message' => 'WebSocket connection test'
            ];


            broadcast(new \Illuminate\Broadcasting\PrivateChannel('admin.test'))->with($testData);

            return [
                'success' => true,
                'message' => 'Real-time connection test successful',
                'config' => $config
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Real-time connection test failed: ' . $e->getMessage(),
                'config' => $this->getRealtimeConfig()
            ];
        }
    }

    public function getActiveConnections(): array
    {


        return [
            'total_connections' => 0,
            'admin_connections' => 0,
            'employee_connections' => 0,
            'channels' => [
                'admin.orders' => 0,
                'admin.inventory' => 0,
                'admin.points' => 0,
            ],
            'last_updated' => now()->toISOString(),
        ];
    }
}