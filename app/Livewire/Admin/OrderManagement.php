<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use App\Services\PointsService;
use App\Services\CacheService;
use Illuminate\Database\Eloquent\Builder;

class OrderManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 25;


    public $showStatusModal = false;
    public $selectedOrder = null;
    public $newStatus = '';
    public $statusNotes = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
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

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedDateFrom()
    {
        $this->resetPage();
    }

    public function updatedDateTo()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->resetPage();
    }

    public function openStatusModal($orderId)
    {
        $this->selectedOrder = Order::with(['employee', 'product'])->find($orderId);
        $this->newStatus = $this->selectedOrder->estado;
        $this->statusNotes = '';
        $this->showStatusModal = true;
    }

    public function closeStatusModal()
    {
        $this->showStatusModal = false;
        $this->selectedOrder = null;
        $this->newStatus = '';
        $this->statusNotes = '';
    }

    public function updateOrderStatus()
    {
        $this->validate([
            'newStatus' => 'required|in:' . implode(',', Order::availableStatuses()),
            'statusNotes' => 'nullable|string|max:500',
        ]);

        try {
            $pointsService = app(PointsService::class);
            $result = $pointsService->updateOrderStatus(
                $this->selectedOrder,
                $this->newStatus,
                $this->statusNotes
            );

            $this->dispatch('orderUpdated', [
                'orderId' => $this->selectedOrder->id,
                'newStatus' => $this->newStatus,
                'message' => $result['message']
            ]);

            $this->dispatch('toast', [
                'message' => $result['message'],
                'type' => 'success'
            ]);

            $this->closeStatusModal();

        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'message' => 'Error al actualizar el estado: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function getOrdersProperty()
    {
        return Order::query()
            ->with(['employee', 'product'])
            ->when($this->search, function (Builder $query) {
                $query->where(function (Builder $q) {
                    $q->where('empleado_nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('producto_nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('id', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function (Builder $query) {
                $query->where('estado', $this->statusFilter);
            })
            ->when($this->dateFrom, function (Builder $query) {
                $query->whereDate('fecha', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function (Builder $query) {
                $query->whereDate('fecha', '<=', $this->dateTo);
            })
            ->orderBy('fecha', 'desc')
            ->paginate($this->perPage);
    }

    public function getStatsProperty()
    {
        $cacheService = app(CacheService::class);
        

        if (!$this->search && !$this->dateFrom && !$this->dateTo) {
            return $cacheService->getAdminStatistics();
        }
        

        $baseQuery = Order::query()
            ->when($this->search, function (Builder $query) {
                $query->where(function (Builder $q) {
                    $q->where('empleado_nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('producto_nombre', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->dateFrom, function (Builder $query) {
                $query->whereDate('fecha', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function (Builder $query) {
                $query->whereDate('fecha', '<=', $this->dateTo);
            });

        return [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('estado', Order::STATUS_PENDING)->count(),
            'in_progress' => (clone $baseQuery)->where('estado', Order::STATUS_IN_PROGRESS)->count(),
            'completed' => (clone $baseQuery)->where('estado', Order::STATUS_COMPLETED)->count(),
            'cancelled' => (clone $baseQuery)->where('estado', Order::STATUS_CANCELLED)->count(),
            'total_points' => (clone $baseQuery)->whereIn('estado', [Order::STATUS_COMPLETED, Order::STATUS_IN_PROGRESS])->sum('puntos_utilizados'),
        ];
    }

    public function render()
    {
        return view('livewire.admin.order-management', [
            'orders' => $this->orders,
            'stats' => $this->stats,
            'availableStatuses' => Order::availableStatuses(),
        ])
        ->extends('layouts.app')
        ->section('content');
    }
}