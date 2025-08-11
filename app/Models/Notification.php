<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'empleado_id',
        'type',
        'title',
        'message',
        'data',
        'read',
        'read_at',
        'created_by',
    ];

    protected $casts = [
        'data' => 'array',
        'read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'empleado_id', 'id_empleado');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'created_by', 'id_empleado');
    }

    public function markAsRead(): void
    {
        if (!$this->read) {
            $this->update([
                'read' => true,
                'read_at' => now(),
            ]);
        }
    }

    public function scopeUnread($query)
    {
        return $query->where('read', false);
    }

    public function scopeForEmployee($query, string $employeeId)
    {
        return $query->where('empleado_id', $employeeId);
    }

    public function scopeForAdmins($query)
    {
        return $query->whereNull('empleado_id');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public static function createNotification(
        ?string $employeeId,
        string $type,
        string $title,
        string $message,
        ?array $data = null,
        ?string $createdBy = null
    ): self {
        return self::create([
            'empleado_id' => $employeeId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'created_by' => $createdBy,
        ]);
    }

    public static function notifyLowStock(Product $product): void
    {
        $message = "El producto '{$product->nombre}' tiene stock bajo ({$product->stock} unidades). Mínimo requerido: {$product->stock_minimo}.";
        
        self::createNotification(
            null,
            'low_stock',
            'Stock Bajo',
            $message,
            [
                'product_id' => $product->id,
                'current_stock' => $product->stock,
                'minimum_stock' => $product->stock_minimo,
            ]
        );

        $product->markLowStockNotified();
    }

    public static function notifyPointsAwarded(string $employeeId, int $points, string $description, ?string $adminId = null): void
    {
        $message = "Se te han otorgado {$points} puntos. Descripción: {$description}";
        
        self::createNotification(
            $employeeId,
            'points_awarded',
            'Puntos Otorgados',
            $message,
            [
                'points' => $points,
                'description' => $description,
            ],
            $adminId
        );
    }

    public static function notifyOrderStatusChange(string $employeeId, Order $order, string $oldStatus, string $newStatus): void
    {
        $message = "Tu pedido #{$order->id} de '{$order->producto_nombre}' ha cambiado de estado: {$oldStatus} → {$newStatus}";
        
        self::createNotification(
            $employeeId,
            'order_update',
            'Estado de Pedido Actualizado',
            $message,
            [
                'order_id' => $order->id,
                'product_name' => $order->producto_nombre,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ]
        );
    }
}
