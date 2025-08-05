<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Employee;

class SufficientPoints implements ValidationRule
{
    protected Employee $employee;
    protected int $requiredPoints;

    public function __construct(Employee $employee, int $requiredPoints)
    {
        $this->employee = $employee;
        $this->requiredPoints = $requiredPoints;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->employee->hasEnoughPoints($this->requiredPoints)) {
            $fail("No tienes suficientes puntos. Necesitas {$this->requiredPoints} puntos pero solo tienes {$this->employee->puntos_totales}.");
        }
    }
}
