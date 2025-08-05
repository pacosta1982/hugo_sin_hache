<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Order;

class AdminOrdersFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        $employee = $this->get('employee');
        return $employee && $employee->is_admin;
    }

    public function rules(): array
    {
        $availableStatuses = Order::availableStatuses();
        
        return [
            'status' => 'nullable|string|in:' . implode(',', $availableStatuses),
            'date_from' => 'nullable|date|before_or_equal:today',
            'date_to' => 'nullable|date|after_or_equal:date_from|before_or_equal:today',
            'search' => 'nullable|string|max:255',
            'per_page' => 'nullable|integer|min:10|max:100',
            'page' => 'nullable|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'El estado seleccionado no es válido.',
            'date_from.date' => 'La fecha de inicio debe ser una fecha válida.',
            'date_from.before_or_equal' => 'La fecha de inicio no puede ser posterior a hoy.',
            'date_to.date' => 'La fecha de fin debe ser una fecha válida.',
            'date_to.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
            'date_to.before_or_equal' => 'La fecha de fin no puede ser posterior a hoy.',
            'search.max' => 'La búsqueda no puede exceder los 255 caracteres.',
            'per_page.integer' => 'El número de elementos por página debe ser un número entero.',
            'per_page.min' => 'Debe mostrar al menos 10 elementos por página.',
            'per_page.max' => 'No se pueden mostrar más de 100 elementos por página.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {

            $dateFrom = $this->input('date_from');
            $dateTo = $this->input('date_to');

            if ($dateFrom && $dateTo) {
                $from = \Carbon\Carbon::parse($dateFrom);
                $to = \Carbon\Carbon::parse($dateTo);
                

                if ($from->diffInDays($to) > 365) {
                    $validator->errors()->add('date_range', 'El rango de fechas es muy amplio. Se recomienda un máximo de 1 año.');
                }
            }
        });
    }

    public function attributes(): array
    {
        return [
            'status' => 'estado',
            'date_from' => 'fecha de inicio',
            'date_to' => 'fecha de fin',
            'search' => 'búsqueda',
            'per_page' => 'elementos por página',
        ];
    }

    protected function prepareForValidation(): void
    {

        if (!$this->has('per_page')) {
            $this->merge(['per_page' => 25]);
        }


        if ($this->has('search')) {
            $this->merge(['search' => trim($this->input('search'))]);
        }
    }
}