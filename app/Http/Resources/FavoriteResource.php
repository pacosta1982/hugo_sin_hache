<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'empleado_id' => $this->empleado_id,
            'producto_id' => $this->producto_id,
            'fecha_agregado' => $this->fecha_agregado,
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