<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Employee;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;

class EmployeeManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = '';
    public $perPage = 25;


    public $showDetailsModal = false;
    public $showEditModal = false;
    public $selectedEmployee = null;
    public $employeeOrders = [];
    public $employeeStats = [];
    

    public $editForm = [
        'nombre' => '',
        'email' => '',
        'puntos_totales' => 0,
        'rol_usuario' => 'Empleado',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'roleFilter' => ['except' => ''],
    ];

    public function mount()
    {
        $employee = request()->get('employee');
        if (!$employee || !$employee->is_admin) {
            abort(403, 'Acceso denegado');
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedRoleFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->roleFilter = '';
        $this->resetPage();
    }

    public function openDetailsModal($employeeId)
    {
        $this->selectedEmployee = Employee::find($employeeId);
        
        if ($this->selectedEmployee) {

            $this->employeeOrders = Order::where('empleado_id', $this->selectedEmployee->id_empleado)
                ->with('product')
                ->orderBy('fecha', 'desc')
                ->limit(10)
                ->get()
                ->toArray();


            $this->employeeStats = [
                'total_orders' => Order::where('empleado_id', $this->selectedEmployee->id_empleado)->count(),
                'pending_orders' => Order::where('empleado_id', $this->selectedEmployee->id_empleado)
                    ->where('estado', Order::STATUS_PENDING)->count(),
                'completed_orders' => Order::where('empleado_id', $this->selectedEmployee->id_empleado)
                    ->where('estado', Order::STATUS_COMPLETED)->count(),
                'total_points_used' => Order::where('empleado_id', $this->selectedEmployee->id_empleado)
                    ->whereIn('estado', [Order::STATUS_COMPLETED, Order::STATUS_IN_PROGRESS])
                    ->sum('puntos_utilizados'),
                'average_order_value' => Order::where('empleado_id', $this->selectedEmployee->id_empleado)
                    ->avg('puntos_utilizados'),
                'last_order_date' => Order::where('empleado_id', $this->selectedEmployee->id_empleado)
                    ->max('fecha'),
            ];
        }

        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedEmployee = null;
        $this->employeeOrders = [];
        $this->employeeStats = [];
    }

    public function openEditModal($employeeId)
    {
        $this->selectedEmployee = Employee::find($employeeId);
        
        if ($this->selectedEmployee) {
            $this->editForm = [
                'nombre' => $this->selectedEmployee->nombre,
                'email' => $this->selectedEmployee->email,
                'puntos_totales' => $this->selectedEmployee->puntos_totales,
                'rol_usuario' => $this->selectedEmployee->rol_usuario,
            ];
        }

        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->selectedEmployee = null;
        $this->editForm = [
            'nombre' => '',
            'email' => '',
            'puntos_totales' => 0,
            'rol_usuario' => 'Empleado',
        ];
    }

    public function updateEmployee()
    {
        $this->validate([
            'editForm.nombre' => 'required|string|max:255',
            'editForm.email' => 'required|email|max:255',
            'editForm.puntos_totales' => 'required|integer|min:0',
            'editForm.rol_usuario' => 'required|in:Empleado,Administrador',
        ], [
            'editForm.nombre.required' => 'El nombre es obligatorio.',
            'editForm.email.required' => 'El email es obligatorio.',
            'editForm.email.email' => 'El email debe ser válido.',
            'editForm.puntos_totales.required' => 'Los puntos son obligatorios.',
            'editForm.puntos_totales.min' => 'Los puntos no pueden ser negativos.',
            'editForm.rol_usuario.in' => 'El rol debe ser Empleado o Administrador.',
        ]);

        if ($this->selectedEmployee) {
            $originalPoints = $this->selectedEmployee->puntos_totales;
            
            $this->selectedEmployee->update([
                'nombre' => $this->editForm['nombre'],
                'email' => $this->editForm['email'],
                'puntos_totales' => $this->editForm['puntos_totales'],
                'rol_usuario' => $this->editForm['rol_usuario'],
            ]);


            if ($originalPoints != $this->editForm['puntos_totales']) {
                \Log::info('Admin updated employee points', [
                    'admin_id' => request()->get('employee')->id_empleado,
                    'employee_id' => $this->selectedEmployee->id_empleado,
                    'employee_name' => $this->selectedEmployee->nombre,
                    'old_points' => $originalPoints,
                    'new_points' => $this->editForm['puntos_totales'],
                    'difference' => $this->editForm['puntos_totales'] - $originalPoints,
                ]);
            }

            session()->flash('message', 'Empleado actualizado exitosamente.');
            $this->closeEditModal();
        }
    }

    public function addPoints($employeeId, $points)
    {
        $employee = Employee::find($employeeId);
        if ($employee && $points > 0) {
            $employee->increment('puntos_totales', $points);
            
            \Log::info('Admin added points to employee', [
                'admin_id' => request()->get('employee')->id_empleado,
                'employee_id' => $employee->id_empleado,
                'employee_name' => $employee->nombre,
                'points_added' => $points,
                'new_total' => $employee->fresh()->puntos_totales,
            ]);

            session()->flash('message', "Se agregaron {$points} puntos a {$employee->nombre}.");
        }
    }

    public function getEmployeesProperty()
    {
        return Employee::query()
            ->when($this->search, function (Builder $query) {
                $query->where(function (Builder $q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('id_empleado', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->roleFilter, function (Builder $query) {
                $query->where('rol_usuario', $this->roleFilter);
            })
            ->orderBy('nombre')
            ->paginate($this->perPage);
    }

    public function getStatsProperty()
    {
        $baseQuery = Employee::query()
            ->when($this->search, function (Builder $query) {
                $query->where(function (Builder $q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->roleFilter, function (Builder $query) {
                $query->where('rol_usuario', $this->roleFilter);
            });

        return [
            'total_employees' => (clone $baseQuery)->count(),
            'total_points_available' => (clone $baseQuery)->sum('puntos_totales'),
            'total_points_redeemed' => (clone $baseQuery)->sum('puntos_canjeados'),
            'admin_count' => (clone $baseQuery)->where('rol_usuario', 'Administrador')->count(),
            'active_users' => Order::distinct('empleado_id')->whereDate('fecha', '>=', now()->subDays(30))->count(),
        ];
    }

    public function getRolesProperty()
    {
        return Employee::distinct('rol_usuario')->pluck('rol_usuario')->filter()->sort()->values()->toArray();
    }

    public function render()
    {
        return view('livewire.admin.employee-management', [
            'employees' => $this->employees,
            'stats' => $this->stats,
            'roles' => $this->roles,
        ])
        ->extends('layouts.app')
        ->section('content');
    }
}