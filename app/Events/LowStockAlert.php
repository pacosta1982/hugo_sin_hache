<?php

namespace App\Events;

use App\Models\Product;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class LowStockAlert implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Product $product,
        public string $alertType = 'low_stock'
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin.inventory'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'inventory.alert';
    }

    public function broadcastWith(): array
    {
        return [
            'product_id' => $this->product->id,
            'product_name' => $this->product->nombre,
            'current_stock' => $this->product->stock,
            'minimum_stock' => $this->product->stock_minimo,
            'alert_type' => $this->alertType,
            'category' => $this->product->categoria,
            'is_critical' => $this->product->stock === 0,
            'timestamp' => now()->toISOString(),
        ];
    }
}
