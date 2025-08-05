<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Public search functionality
    }

    public function rules(): array
    {
        return [
            'search' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'sort_by' => 'nullable|string|in:costo_puntos,nombre,categoria,created_at',
            'sort_direction' => 'nullable|string|in:asc,desc',
            'available_only' => 'nullable|boolean',
            'max_points' => 'nullable|integer|min:0',
            'per_page' => 'nullable|integer|min:6|max:48',
            'page' => 'nullable|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'search.max' => 'La búsqueda no puede exceder los 255 caracteres.',
            'category.max' => 'La categoría no puede exceder los 100 caracteres.',
            'sort_by.in' => 'El campo de ordenamiento no es válido.',
            'sort_direction.in' => 'La dirección de ordenamiento debe ser "asc" o "desc".',
            'available_only.boolean' => 'El filtro de disponibilidad debe ser verdadero o falso.',
            'max_points.integer' => 'El máximo de puntos debe ser un número entero.',
            'max_points.min' => 'El máximo de puntos no puede ser negativo.',
            'per_page.integer' => 'El número de elementos por página debe ser un número entero.',
            'per_page.min' => 'Debe mostrar al menos 6 elementos por página.',
            'per_page.max' => 'No se pueden mostrar más de 48 elementos por página.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $employee = $this->get('employee');
            $maxPoints = $this->input('max_points');


            if ($employee && $maxPoints && $maxPoints > $employee->puntos_totales) {
                $validator->errors()->add('max_points', 'No puedes filtrar por más puntos de los que tienes disponibles.');
            }
        });
    }

    public function attributes(): array
    {
        return [
            'search' => 'búsqueda',
            'category' => 'categoría',
            'sort_by' => 'ordenar por',
            'sort_direction' => 'dirección de ordenamiento',
            'available_only' => 'solo disponibles',
            'max_points' => 'puntos máximos',
            'per_page' => 'elementos por página',
        ];
    }

    protected function prepareForValidation(): void
    {

        $defaults = [
            'sort_by' => 'costo_puntos',
            'sort_direction' => 'asc',
            'available_only' => true,
            'per_page' => 12,
        ];

        foreach ($defaults as $key => $value) {
            if (!$this->has($key)) {
                $this->merge([$key => $value]);
            }
        }


        if ($this->has('search')) {
            $this->merge(['search' => trim($this->input('search'))]);
        }


        if ($this->has('category')) {
            $this->merge(['category' => trim($this->input('category'))]);
        }


        if ($this->has('available_only')) {
            $this->merge(['available_only' => filter_var($this->input('available_only'), FILTER_VALIDATE_BOOLEAN)]);
        }
    }
}