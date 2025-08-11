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
    public $employeeTransactions = [];
    

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
        if (request()->expectsJson()) {
            $employee = request()->get('employee');
            if (!$employee || !$employee->is_admin) {
                abort(403, 'Acceso denegado');
            }
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

            $this->employeeTransactions = \App\Models\PointTransaction::where('empleado_id', $this->selectedEmployee->id_empleado)
                ->with('admin')
                ->orderBy('created_at', 'desc')
                ->limit(15)
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
        $this->employeeTransactions = [];
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

    public $showPointsModal = false;
    public $pointsForm = [
        'employee_id' => '',
        'points' => '',
        'description' => '',
        'type' => 'earned',
    ];

    public function addPoints($employeeId, $points)
    {
        $employee = Employee::find($employeeId);
        if ($employee && $points > 0) {
            $adminId = request()->attributes->get('firebase_user')['uid'] ?? null;
            
            $employee->awardPoints(
                $points,
                'Admin manual point adjustment',
                $adminId
            );

            session()->flash('message', "Se agregaron {$points} puntos a {$employee->nombre}.");
        }
    }

    public function openPointsModal($employeeId)
    {
        $employee = Employee::find($employeeId);
        if ($employee) {
            $this->pointsForm = [
                'employee_id' => $employeeId,
                'points' => '',
                'description' => '',
                'type' => 'earned',
            ];
            $this->showPointsModal = true;
        }
    }

    public function closePointsModal()
    {
        $this->showPointsModal = false;
        $this->pointsForm = [
            'employee_id' => '',
            'points' => '',
            'description' => '',
            'type' => 'earned',
        ];
        $this->resetValidation();
    }

    public function adjustPoints()
    {
        $this->validate([
            'pointsForm.points' => 'required|integer|min:1|max:10000',
            'pointsForm.description' => 'required|string|max:255',
            'pointsForm.type' => 'required|in:earned,adjustment',
        ]);

        $employee = Employee::find($this->pointsForm['employee_id']);
        if ($employee) {
            $adminId = request()->attributes->get('firebase_user')['uid'] ?? null;
            
            if ($this->pointsForm['type'] === 'earned') {
                $employee->awardPoints(
                    (int) $this->pointsForm['points'],
                    $this->pointsForm['description'],
                    $adminId
                );
                $message = "Se otorgaron {$this->pointsForm['points']} puntos a {$employee->nombre}";
            } else {
                \App\Models\PointTransaction::recordTransaction(
                    $employee->id_empleado,
                    'adjustment',
                    (int) $this->pointsForm['points'],
                    $this->pointsForm['description'],
                    $adminId
                );
                
                $employee->update([
                    'puntos_totales' => $employee->puntos_totales + (int) $this->pointsForm['points']
                ]);
                
                $message = "Se ajustaron {$this->pointsForm['points']} puntos para {$employee->nombre}";
            }

            session()->flash('message', $message);
            $this->closePointsModal();
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