<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'categoria' => $this->categoria,
            'costo_puntos' => $this->costo_puntos,
            'stock' => $this->stock,
            'stock_unlimited' => $this->has_unlimited_stock,
            'activo' => $this->activo,
            'integra_jira' => $this->integra_jira,
            'envia_email' => $this->envia_email,
            'terminos_condiciones' => $this->terminos_condiciones,
            'is_available' => $this->is_available,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}