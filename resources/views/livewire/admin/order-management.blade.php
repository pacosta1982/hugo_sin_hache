<div>
    
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Gestión de Pedidos</h1>
        <p class="mt-2 text-gray-600">
            Administra todos los pedidos del sistema
        </p>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-8">
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</div>
                <div class="text-sm text-gray-500">Total</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-yellow-600">{{ number_format($stats['pending']) }}</div>
                <div class="text-sm text-gray-500">Pendientes</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-blue-600">{{ number_format($stats['in_progress']) }}</div>
                <div class="text-sm text-gray-500">En Curso</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-green-600">{{ number_format($stats['completed']) }}</div>
                <div class="text-sm text-gray-500">Completados</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-red-600">{{ number_format($stats['cancelled']) }}</div>
                <div class="text-sm text-gray-500">Cancelados</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-purple-600">{{ number_format($stats['total_points']) }}</div>
                <div class="text-sm text-gray-500">Puntos Total</div>
            </div>
        </div>
    </div>

    
    <div class="card mb-6">
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                
                <div class="md:col-span-2">
                    <label class="form-label">Buscar pedidos</label>
                    <input type="text" 
                           wire:model.live.debounce.300ms="search"
                           class="form-input" 
                           placeholder="ID, empleado o producto...">
                </div>

                
                <div>
                    <label class="form-label">Estado</label>
                    <select wire:model.live="statusFilter" class="form-input">
                        <option value="">Todos los estados</option>
                        @foreach($availableStatuses as $status)
                            <option value="{{ $status }}">{{ $status }}</option>
                        @endforeach
                    </select>
                </div>

                
                <div>
                    <label class="form-label">Desde</label>
                    <input type="date" 
                           wire:model.live="dateFrom"
                           class="form-input">
                </div>

                
                <div>
                    <label class="form-label">Hasta</label>
                    <input type="date" 
                           wire:model.live="dateTo"
                           class="form-input">
                </div>
            </div>

            <div class="mt-4 flex justify-between items-center">
                <button wire:click="clearFilters" class="btn-secondary btn-sm">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                    </svg>
                    Limpiar filtros
                </button>

                <div class="text-sm text-gray-500">
                    Mostrando {{ $orders->count() }} de {{ $orders->total() }} pedidos
                </div>
            </div>
        </div>
    </div>

    
    <div class="card">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pedido
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Empleado
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Producto
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Puntos
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Fecha
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">#{{ $order->id }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $order->empleado_nombre }}</div>
                                <div class="text-sm text-gray-500">{{ $order->empleado_id }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $order->producto_nombre }}</div>
                                @if($order->observaciones)
                                    <div class="text-sm text-gray-500 truncate max-w-xs">{{ $order->observaciones }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-blue-600">{{ number_format($order->puntos_utilizados) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="status-badge 
                                    {{ $order->estado === 'Pendiente' ? 'status-pending' : '' }}
                                    {{ $order->estado === 'En curso' ? 'status-in-progress' : '' }}
                                    {{ $order->estado === 'Realizado' ? 'status-completed' : '' }}
                                    {{ $order->estado === 'Cancelado' ? 'status-cancelled' : '' }}">
                                    {{ $order->estado }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->fecha->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="openStatusModal({{ $order->id }})" 
                                        class="text-blue-600 hover:text-blue-900 mr-3">
                                    Cambiar Estado
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <p class="text-lg font-medium">No se encontraron pedidos</p>
                                    <p class="text-sm">Intenta ajustar los filtros de búsqueda</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

    
    @if($showStatusModal && $selectedOrder)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full m-4">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        Actualizar Estado del Pedido #{{ $selectedOrder->id }}
                    </h3>
                </div>
                
                <div class="px-6 py-4">
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">
                            <strong>Empleado:</strong> {{ $selectedOrder->empleado_nombre }}
                        </p>
                        <p class="text-sm text-gray-600 mb-2">
                            <strong>Producto:</strong> {{ $selectedOrder->producto_nombre }}
                        </p>
                        <p class="text-sm text-gray-600 mb-4">
                            <strong>Estado actual:</strong> 
                            <span class="status-badge 
                                {{ $selectedOrder->estado === 'Pendiente' ? 'status-pending' : '' }}
                                {{ $selectedOrder->estado === 'En curso' ? 'status-in-progress' : '' }}
                                {{ $selectedOrder->estado === 'Realizado' ? 'status-completed' : '' }}
                                {{ $selectedOrder->estado === 'Cancelado' ? 'status-cancelled' : '' }}">
                                {{ $selectedOrder->estado }}
                            </span>
                        </p>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Nuevo Estado</label>
                        <select wire:model="newStatus" class="form-input">
                            @foreach($availableStatuses as $status)
                                <option value="{{ $status }}">{{ $status }}</option>
                            @endforeach
                        </select>
                        @error('newStatus')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Notas (opcional)</label>
                        <textarea wire:model="statusNotes" 
                                  class="form-input" 
                                  rows="3" 
                                  placeholder="Agregar notas sobre el cambio de estado..."></textarea>
                        @error('statusNotes')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button wire:click="closeStatusModal" class="btn-secondary">
                        Cancelar
                    </button>
                    <button wire:click="updateOrderStatus" class="btn-primary">
                        Actualizar Estado
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('toast', (event) => {
            window.showToast(event.message, event.type);
        });

        Livewire.on('orderUpdated', (event) => {
            // Could add real-time notifications here
            console.log('Order updated:', event);
        });
    });

    // Auto-refresh every 30 seconds for real-time updates
    setInterval(() => {
        if (document.visibilityState === 'visible') {
            Livewire.dispatch('$refresh');
        }
    }, 30000);
</script>
@endpush