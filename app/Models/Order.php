<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'empleado_id',
        'producto_id',
        'fecha',
        'estado',
        'puntos_utilizados',
        'producto_nombre',
        'empleado_nombre',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'puntos_utilizados' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public static function availableStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_PROCESSING,
            self::STATUS_COMPLETED,
            self::STATUS_CANCELLED,
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'empleado_id', 'id_empleado');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'producto_id');
    }

    public function scopeByEmployee($query, string $employeeId)
    {
        return $query->where('empleado_id', $employeeId);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('estado', $status);
    }

    public function scopePending($query)
    {
        return $query->where('estado', self::STATUS_PENDING);
    }

    public function scopeInProgress($query)
    {
        return $query->where('estado', self::STATUS_PROCESSING);
    }

    public function scopeCompleted($query)
    {
        return $query->where('estado', self::STATUS_COMPLETED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('estado', self::STATUS_CANCELLED);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('fecha', '>=', now()->subDays($days));
    }

    public function getIsPendingAttribute(): bool
    {
        return $this->estado === self::STATUS_PENDING;
    }

    public function getIsInProgressAttribute(): bool
    {
        return $this->estado === self::STATUS_PROCESSING;
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->estado === self::STATUS_COMPLETED;
    }

    public function getIsCancelledAttribute(): bool
    {
        return $this->estado === self::STATUS_CANCELLED;
    }

    public function getCanBeCancelledAttribute(): bool
    {
        return in_array($this->estado, [self::STATUS_PENDING, self::STATUS_PROCESSING]);
    }

    public function markAsInProgress(): bool
    {
        if ($this->estado === self::STATUS_PENDING) {
            return $this->update(['estado' => self::STATUS_PROCESSING]);
        }
        return false;
    }

    public function markAsCompleted(): bool
    {
        if (in_array($this->estado, [self::STATUS_PENDING, self::STATUS_PROCESSING])) {
            return $this->update(['estado' => self::STATUS_COMPLETED]);
        }
        return false;
    }

    public function cancel(): bool
    {
        if ($this->can_be_cancelled) {
            return $this->update(['estado' => self::STATUS_CANCELLED]);
        }
        return false;
    }

    public function getEstadoDisplayAttribute(): string
    {
        $statusLabels = [
            self::STATUS_PENDING => 'Pendiente',
            self::STATUS_PROCESSING => 'En proceso',
            self::STATUS_COMPLETED => 'Completado',
            self::STATUS_CANCELLED => 'Cancelado',
        ];

        return $statusLabels[$this->estado] ?? $this->estado;
    }
}