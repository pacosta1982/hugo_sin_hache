<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'empleado_id' => $this->empleado_id,
            'producto_id' => $this->producto_id,
            'fecha' => $this->fecha,
            'estado' => $this->estado,
            'puntos_utilizados' => $this->puntos_utilizados,
            'producto_nombre' => $this->producto_nombre,
            'empleado_nombre' => $this->empleado_nombre,
            'observaciones' => $this->observaciones,
            'is_pending' => $this->is_pending,
            'is_in_progress' => $this->is_in_progress,
            'is_completed' => $this->is_completed,
            'is_cancelled' => $this->is_cancelled,
            'can_be_cancelled' => $this->can_be_cancelled,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            

            'employee' => $this->whenLoaded('employee', function () {
                return new EmployeeResource($this->employee);
            }),
            'product' => $this->whenLoaded('product', function () {
                return new ProductResource($this->product);
            }),
        ];
    }
}