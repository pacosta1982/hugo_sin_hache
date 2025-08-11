<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'descripcion',
        'categoria',
        'category_id',
        'costo_puntos',
        'stock',
        'stock_inicial',
        'stock_minimo',
        'notificar_stock_bajo',
        'ultima_alerta_stock',
        'activo',
        'integra_jira',
        'envia_email',
        'terminos_condiciones',
        'imagen_url',
    ];

    protected $casts = [
        'costo_puntos' => 'integer',
        'stock' => 'integer',
        'stock_inicial' => 'integer',
        'stock_minimo' => 'integer',
        'notificar_stock_bajo' => 'boolean',
        'ultima_alerta_stock' => 'datetime',
        'activo' => 'boolean',
        'integra_jira' => 'boolean',
        'envia_email' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'producto_id');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class, 'producto_id');
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('categoria', $category);
    }

    public function scopeByCategoryId($query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeAvailable($query)
    {
        return $query->where('activo', true)
                    ->where(function ($q) {
                        $q->where('stock', '>', 0)
                          ->orWhere('stock', -1);
                    });
    }

    public function getIsAvailableAttribute(): bool
    {
        return $this->activo && ($this->stock > 0 || $this->stock === -1);
    }

    public function getHasUnlimitedStockAttribute(): bool
    {
        return $this->stock === -1;
    }

    public function getStockLimitadoAttribute(): bool
    {
        return $this->stock !== -1;
    }

    public function getStockDisponibleAttribute(): ?int
    {
        return $this->stock === -1 ? null : $this->stock;
    }

    public function hasStock(int $quantity = 1): bool
    {
        return $this->stock === -1 || $this->stock >= $quantity;
    }

    public function decrementStock(int $quantity = 1): void
    {
        if ($this->stock !== -1) {
            $this->decrement('stock', $quantity);
        }
    }

    public function incrementStock(int $quantity = 1): void
    {
        if ($this->stock !== -1) {
            $this->increment('stock', $quantity);
        }
    }

    public function getIsLowStockAttribute(): bool
    {
        if ($this->stock === -1) {
            return false;
        }
        return $this->stock <= $this->stock_minimo;
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->stock === -1) {
            return 'unlimited';
        }
        if ($this->stock === 0) {
            return 'out_of_stock';
        }
        if ($this->stock <= $this->stock_minimo) {
            return 'low_stock';
        }
        return 'in_stock';
    }

    public function getStockPercentageAttribute(): ?float
    {
        if ($this->stock === -1 || $this->stock_inicial === 0) {
            return null;
        }
        return ($this->stock / $this->stock_inicial) * 100;
    }

    public function scopeLowStock($query)
    {
        return $query->where('stock', '!=', -1)
                    ->whereColumn('stock', '<=', 'stock_minimo');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock', 0);
    }

    public function shouldNotifyLowStock(): bool
    {
        if (!$this->notificar_stock_bajo || !$this->is_low_stock) {
            return false;
        }


        if ($this->ultima_alerta_stock && $this->ultima_alerta_stock->diffInHours() < 24) {
            return false;
        }

        return true;
    }

    public function markLowStockNotified(): void
    {
        $this->update(['ultima_alerta_stock' => now()]);
    }

    public function scopeInStock($query)
    {
        return $query->where('activo', true)
                    ->where(function ($q) {
                        $q->where('stock', '>', 0)
                          ->orWhere('stock', -1);
                    });
    }

    public function getPuntosRequeridosAttribute(): int
    {
        return $this->costo_puntos;
    }
}