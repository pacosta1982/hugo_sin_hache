<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Order;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {

        return true;
    }

    public function rules(): array
    {
        $availableStatuses = Order::availableStatuses();
        
        return [
            'status' => 'required|string|in:' . implode(',', $availableStatuses),
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'El estado es requerido.',
            'status.in' => 'El estado seleccionado no es válido.',
            'notes.max' => 'Las notas no pueden exceder los 500 caracteres.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $order = $this->route('order');
            $newStatus = $this->input('status');

            if (!$order) {
                $validator->errors()->add('order', 'Pedido no encontrado.');
                return;
            }


            if (!$this->isValidStatusTransition($order->estado, $newStatus)) {
                $validator->errors()->add('status', 'No se puede cambiar el estado de "' . $order->estado . '" a "' . $newStatus . '".');
            }
        });
    }

    private function isValidStatusTransition(string $currentStatus, string $newStatus): bool
    {

        $validTransitions = [
            Order::STATUS_PENDING => [Order::STATUS_PROCESSING, Order::STATUS_CANCELLED],
            Order::STATUS_PROCESSING => [Order::STATUS_COMPLETED, Order::STATUS_CANCELLED],
            Order::STATUS_COMPLETED => [],
            Order::STATUS_CANCELLED => [],
        ];


        if ($currentStatus === $newStatus) {
            return true;
        }

        return in_array($newStatus, $validTransitions[$currentStatus] ?? []);
    }

    public function attributes(): array
    {
        return [
            'status' => 'estado',
            'notes' => 'notas',
        ];
    }
}