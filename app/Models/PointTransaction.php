<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PointTransaction extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'empleado_id',
        'type',
        'points',
        'description',
        'admin_id',
        'metadata',
    ];

    protected $casts = [
        'points' => 'integer',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'empleado_id', 'id_empleado');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'admin_id', 'id_empleado');
    }

    public static function recordTransaction(string $empleadoId, string $type, int $points, string $description, ?string $adminId = null, ?array $metadata = null): self
    {
        return self::create([
            'empleado_id' => $empleadoId,
            'type' => $type,
            'points' => $points,
            'description' => $description,
            'admin_id' => $adminId,
            'metadata' => $metadata,
        ]);
    }
}
