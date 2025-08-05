<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:100',
            'sort_by' => 'sometimes|string|max:50',
            'sort_order' => 'sometimes|in:asc,desc',
            'search' => 'sometimes|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'page.min' => 'La página debe ser mayor a 0.',
            'per_page.min' => 'Los elementos por página deben ser mayor a 0.',
            'per_page.max' => 'Máximo 100 elementos por página.',
            'sort_order.in' => 'El orden debe ser asc o desc.',
            'search.max' => 'La búsqueda no puede exceder 255 caracteres.',
        ];
    }

    public function getPagination(): array
    {
        return [
            'page' => $this->input('page', 1),
            'per_page' => min($this->input('per_page', 15), 100),
        ];
    }

    public function getSorting(): array
    {
        return [
            'sort_by' => $this->input('sort_by'),
            'sort_order' => $this->input('sort_order', 'desc'),
        ];
    }
}
