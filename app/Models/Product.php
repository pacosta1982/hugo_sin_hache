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
        'costo_puntos',
        'stock',
        'activo',
        'integra_jira',
        'envia_email',
        'terminos_condiciones',
        'imagen_url',
    ];

    protected $casts = [
        'costo_puntos' => 'integer',
        'stock' => 'integer',
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

    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('categoria', $category);
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
}