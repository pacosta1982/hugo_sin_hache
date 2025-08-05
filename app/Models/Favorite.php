<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorite extends Model
{
    protected $fillable = [
        'empleado_id',
        'producto_id',
        'fecha_agregado',
    ];

    protected $casts = [
        'fecha_agregado' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

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

    public function scopeByProduct($query, int $productId)
    {
        return $query->where('producto_id', $productId);
    }

    public function scopeWithActiveProducts($query)
    {
        return $query->whereHas('product', function ($q) {
            $q->where('activo', true);
        });
    }

    public static function toggle(string $employeeId, int $productId): array
    {
        \Log::info('Favorite::toggle called', [
            'employee_id' => $employeeId,
            'product_id' => $productId,
        ]);
        
        $favorite = self::where('empleado_id', $employeeId)
                       ->where('producto_id', $productId)
                       ->first();

        \Log::info('Favorite::toggle - existing favorite found', [
            'employee_id' => $employeeId,
            'product_id' => $productId,
            'existing_favorite' => $favorite ? $favorite->id : 'none',
        ]);

        if ($favorite) {
            $favorite->delete();
            \Log::info('Favorite::toggle - removed favorite', [
                'employee_id' => $employeeId,
                'product_id' => $productId,
                'deleted_favorite_id' => $favorite->id,
            ]);
            return ['added' => false, 'favorite' => null];
        } else {
            $favorite = self::create([
                'empleado_id' => $employeeId,
                'producto_id' => $productId,
                'fecha_agregado' => now(),
            ]);
            \Log::info('Favorite::toggle - created favorite', [
                'employee_id' => $employeeId,
                'product_id' => $productId,
                'created_favorite_id' => $favorite->id,
            ]);
            return ['added' => true, 'favorite' => $favorite];
        }
    }

    public static function isFavorite(string $employeeId, int $productId): bool
    {
        return self::where('empleado_id', $employeeId)
                  ->where('producto_id', $productId)
                  ->exists();
    }
}