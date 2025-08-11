<?php

namespace App\Livewire\Admin;

use App\Models\Employee;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AwardPoints extends Component
{
    public $selectedEmployeeId = '';
    public $points = '';
    public $description = '';
    public $employees = [];
    public $recentTransactions = [];

    public function mount()
    {
        $this->employees = Employee::orderBy('nombre')->get();
        $this->loadRecentTransactions();
    }

    public function awardPoints()
    {
        $this->validate([
            'selectedEmployeeId' => 'required|exists:employees,id_empleado',
            'points' => 'required|integer|min:1|max:10000',
            'description' => 'required|string|max:255',
        ]);

        $employee = Employee::find($this->selectedEmployeeId);
        $adminId = request()->attributes->get('firebase_user')['uid'] ?? null;

        $employee->awardPoints(
            (int) $this->points,
            $this->description,
            $adminId
        );

        session()->flash('success', "Se otorgaron {$this->points} puntos a {$employee->nombre}");
        
        $this->reset(['selectedEmployeeId', 'points', 'description']);
        $this->loadRecentTransactions();
    }

    protected function loadRecentTransactions()
    {
        $this->recentTransactions = \App\Models\PointTransaction::with(['employee', 'admin'])
            ->latest()
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.award-points');
    }
}
