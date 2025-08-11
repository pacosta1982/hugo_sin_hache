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

class NewOrderCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Order $order
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
        return 'order.created';
    }

    public function broadcastWith(): array
    {
        return [
            'order_id' => $this->order->id,
            'employee_id' => $this->order->employee->id_empleado,
            'employee_name' => $this->order->employee->nombre,
            'product_id' => $this->order->product->id,
            'product_name' => $this->order->product->nombre,
            'points_used' => $this->order->puntos_utilizados,
            'status' => $this->order->estado,
            'created_at' => $this->order->created_at->toISOString(),
            'requires_processing' => true,
        ];
    }
}
