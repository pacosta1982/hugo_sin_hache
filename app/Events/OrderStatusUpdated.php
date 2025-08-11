<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Order $order,
        public string $previousStatus
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("employee.{$this->order->employee->id_empleado}"),
            new PrivateChannel('admin.orders'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'order.status.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'order_id' => $this->order->id,
            'new_status' => $this->order->estado,
            'previous_status' => $this->previousStatus,
            'product_name' => $this->order->product->nombre,
            'employee_name' => $this->order->employee->nombre,
            'points_used' => $this->order->puntos_utilizados,
            'updated_at' => $this->order->updated_at->toISOString(),
            'status_text' => $this->getStatusText($this->order->estado),
        ];
    }

    private function getStatusText(string $status): string
    {
        return match($status) {
            'pending' => 'Pendiente',
            'processing' => 'En Proceso',
            'completed' => 'Completado',
            'cancelled' => 'Cancelado',
            default => ucfirst($status)
        };
    }
}
