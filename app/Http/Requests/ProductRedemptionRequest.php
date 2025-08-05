<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Product;

class ProductRedemptionRequest extends FormRequest
{
    public function authorize(): bool
    {

        $employee = $this->get('employee');
        $product = $this->route('product');

        return $employee && $product;
    }

    public function rules(): array
    {
        return [
            'observaciones' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'observaciones.max' => 'Las observaciones no pueden exceder los 500 caracteres.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $employee = $this->get('employee');
            $product = $this->route('product');

            if (!$product) {
                $validator->errors()->add('product', 'Producto no encontrado.');
                return;
            }


            if (!$product->is_available) {
                $validator->errors()->add('product', 'El producto no está disponible para canje.');
            }


            if (!$product->hasStock()) {
                $validator->errors()->add('product', 'No hay stock disponible para este producto.');
            }


            if ($employee && !$employee->hasEnoughPoints($product->costo_puntos)) {
                $validator->errors()->add('points', 'No tienes suficientes puntos para este producto.');
            }
        });
    }

    public function attributes(): array
    {
        return [
            'observaciones' => 'observaciones',
        ];
    }
}