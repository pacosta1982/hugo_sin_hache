<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_empleado' => $this->id_empleado,
            'nombre' => $this->nombre,
            'email' => $this->email,
            'puntos_totales' => $this->puntos_totales,
            'puntos_canjeados' => $this->puntos_canjeados,
            'rol_usuario' => $this->rol_usuario,
            'is_admin' => $this->is_admin,
            'puntos_disponibles' => $this->puntos_disponibles,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}