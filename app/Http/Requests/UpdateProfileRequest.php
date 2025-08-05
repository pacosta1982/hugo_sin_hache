<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Employee;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        $employee = $this->get('employee');
        return $employee !== null;
    }

    public function rules(): array
    {
        $employee = $this->get('employee');
        $employeeId = $employee ? $employee->id_empleado : null;

        return [
            'nombre' => 'required|string|max:255|min:2',
            'email' => [
                'required',
                'email',
                'max:255',

                'unique:employees,email,' . $employeeId . ',id_empleado'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es requerido.',
            'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
            'nombre.max' => 'El nombre no puede exceder los 255 caracteres.',
            'email.required' => 'El email es requerido.',
            'email.email' => 'El email debe tener un formato válido.',
            'email.unique' => 'Este email ya está siendo utilizado por otro usuario.',
            'email.max' => 'El email no puede exceder los 255 caracteres.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $employee = $this->get('employee');
            
            if (!$employee) {
                $validator->errors()->add('auth', 'Usuario no autenticado.');
                return;
            }



        });
    }

    public function attributes(): array
    {
        return [
            'nombre' => 'nombre',
            'email' => 'correo electrónico',
        ];
    }
}