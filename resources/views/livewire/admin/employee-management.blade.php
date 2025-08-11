<div>
    
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Gestión de Empleados</h1>
        <p class="mt-2 text-gray-600">
            Administra todos los empleados del sistema
        </p>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_employees']) }}</div>
                <div class="text-sm text-gray-500">Total Empleados</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_points_available']) }}</div>
                <div class="text-sm text-gray-500">Puntos Disponibles</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-green-600">{{ number_format($stats['total_points_redeemed']) }}</div>
                <div class="text-sm text-gray-500">Puntos Canjeados</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-purple-600">{{ number_format($stats['admin_count']) }}</div>
                <div class="text-sm text-gray-500">Administradores</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-orange-600">{{ number_format($stats['active_users']) }}</div>
                <div class="text-sm text-gray-500">Activos (30d)</div>
            </div>
        </div>
    </div>

    
    <div class="card mb-6">
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                
                <div class="md:col-span-2">
                    <label class="form-label">Buscar empleados</label>
                    <input type="text" 
                           wire:model.live.debounce.300ms="search"
                           class="form-input" 
                           placeholder="Nombre, email o ID...">
                </div>

                
                <div>
                    <label class="form-label">Rol</label>
                    <select wire:model.live="roleFilter" class="form-input">
                        <option value="">Todos los roles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}">{{ $role }}</option>
                        @endforeach
                    </select>
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
                    Mostrando {{ $employees->count() }} de {{ $employees->total() }} empleados
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
                            Empleado
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Rol
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Puntos Disponibles
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Puntos Canjeados
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Registro
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($employees as $employee)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                            <span class="text-white font-medium text-sm">
                                                {{ strtoupper(substr($employee->nombre, 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $employee->nombre }}</div>
                                        <div class="text-sm text-gray-500">{{ $employee->id_empleado }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $employee->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $employee->rol_usuario === 'Administrador' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $employee->rol_usuario }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-blue-600">{{ number_format($employee->puntos_totales) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-green-600">{{ number_format($employee->puntos_canjeados) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $employee->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <button wire:click="openDetailsModal('{{ $employee->id_empleado }}')" 
                                            class="text-blue-600 hover:text-blue-900 px-3 py-1 rounded bg-blue-50 hover:bg-blue-100">
                                        <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Ver
                                    </button>
                                    <button wire:click="openEditModal('{{ $employee->id_empleado }}')" 
                                            class="text-teal-600 hover:text-teal-900 px-3 py-1 rounded bg-teal-50 hover:bg-teal-100">
                                        <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Editar
                                    </button>
                                    <button wire:click="openPointsModal('{{ $employee->id_empleado }}')" 
                                            class="text-green-600 hover:text-green-900 px-3 py-1 rounded bg-green-50 hover:bg-green-100">
                                        <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Puntos
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                    </svg>
                                    <p class="text-lg font-medium">No se encontraron empleados</p>
                                    <p class="text-sm">Intenta ajustar los filtros de búsqueda</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($employees->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $employees->links() }}
            </div>
        @endif
    </div>

    
    @if($showDetailsModal && $selectedEmployee)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full m-4 max-h-screen overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">
                            Detalles del Empleado: {{ $selectedEmployee->nombre }}
                        </h3>
                        <button wire:click="closeDetailsModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="px-6 py-4">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="text-lg font-medium">Información Personal</h4>
                            </div>
                            <div class="card-body">
                                <div class="space-y-3">
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">ID:</span>
                                        <span class="ml-2 text-sm text-gray-900">{{ $selectedEmployee->id_empleado }}</span>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Email:</span>
                                        <span class="ml-2 text-sm text-gray-900">{{ $selectedEmployee->email }}</span>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Rol:</span>
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $selectedEmployee->rol_usuario === 'Administrador' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $selectedEmployee->rol_usuario }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Registro:</span>
                                        <span class="ml-2 text-sm text-gray-900">{{ $selectedEmployee->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4 class="text-lg font-medium">Estadísticas</h4>
                            </div>
                            <div class="card-body">
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-500">Puntos Disponibles:</span>
                                        <span class="text-sm font-bold text-blue-600">{{ number_format($selectedEmployee->puntos_totales) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-500">Puntos Canjeados:</span>
                                        <span class="text-sm font-bold text-green-600">{{ number_format($selectedEmployee->puntos_canjeados) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-500">Total Pedidos:</span>
                                        <span class="text-sm font-bold text-gray-900">{{ number_format($employeeStats['total_orders']) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-500">Pedidos Pendientes:</span>
                                        <span class="text-sm font-bold text-yellow-600">{{ number_format($employeeStats['pending_orders']) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-500">Pedidos Completados:</span>
                                        <span class="text-sm font-bold text-green-600">{{ number_format($employeeStats['completed_orders']) }}</span>
                                    </div>
                                    @if($employeeStats['last_order_date'])
                                        <div class="flex justify-between">
                                            <span class="text-sm font-medium text-gray-500">Último Pedido:</span>
                                            <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($employeeStats['last_order_date'])->format('d/m/Y') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    @if(count($employeeOrders) > 0)
                        <div class="card">
                            <div class="card-header">
                                <h4 class="text-lg font-medium">Pedidos Recientes</h4>
                            </div>
                            <div class="card-body">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Puntos</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($employeeOrders as $order)
                                                <tr>
                                                    <td class="px-4 py-2 text-sm font-medium text-gray-900">#{{ $order['id'] }}</td>
                                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $order['producto_nombre'] }}</td>
                                                    <td class="px-4 py-2 text-sm font-medium text-blue-600">{{ number_format($order['puntos_utilizados']) }}</td>
                                                    <td class="px-4 py-2">
                                                        <span class="status-badge 
                                                            {{ $order['estado'] === 'Pendiente' ? 'status-pending' : '' }}
                                                            {{ $order['estado'] === 'En curso' ? 'status-in-progress' : '' }}
                                                            {{ $order['estado'] === 'Realizado' ? 'status-completed' : '' }}
                                                            {{ $order['estado'] === 'Cancelado' ? 'status-cancelled' : '' }}">
                                                            {{ $order['estado'] }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-2 text-sm text-gray-500">
                                                        {{ \Carbon\Carbon::parse($order['fecha'])->format('d/m/Y') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card">
                            <div class="card-body text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <p class="text-gray-500">Este empleado no ha realizado pedidos aún</p>
                            </div>
                        </div>
                    @endif

                    
                    @if(count($employeeTransactions) > 0)
                        <div class="card mt-6">
                            <div class="card-header">
                                <h4 class="text-lg font-medium">Historial de Puntos</h4>
                            </div>
                            <div class="card-body">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Puntos</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Admin</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($employeeTransactions as $transaction)
                                                <tr>
                                                    <td class="px-4 py-2">
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                            @if($transaction['type'] === 'earned') bg-green-100 text-green-800 
                                                            @elseif($transaction['type'] === 'spent') bg-red-100 text-red-800
                                                            @else bg-yellow-100 text-yellow-800 @endif">
                                                            {{ ucfirst($transaction['type']) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-2 text-sm font-medium text-gray-900">
                                                        @if($transaction['type'] === 'spent')-@endif{{ number_format($transaction['points']) }}
                                                    </td>
                                                    <td class="px-4 py-2 text-sm text-gray-500">{{ $transaction['description'] }}</td>
                                                    <td class="px-4 py-2 text-sm text-gray-500">
                                                        {{ $transaction['admin']['nombre'] ?? 'Sistema' }}
                                                    </td>
                                                    <td class="px-4 py-2 text-sm text-gray-500">
                                                        {{ \Carbon\Carbon::parse($transaction['created_at'])->format('d/m/Y H:i') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    
    @if($showEditModal && $selectedEmployee)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full m-4">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">
                            Editar Empleado: {{ $selectedEmployee->nombre }}
                        </h3>
                        <button wire:click="closeEditModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <form wire:submit.prevent="updateEmployee">
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label class="form-label">Nombre</label>
                            <input type="text" wire:model="editForm.nombre" class="form-input w-full">
                            @error('editForm.nombre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="form-label">Email</label>
                            <input type="email" wire:model="editForm.email" class="form-input w-full">
                            @error('editForm.email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="form-label">Puntos Totales</label>
                            <input type="number" wire:model="editForm.puntos_totales" class="form-input w-full" min="0">
                            @error('editForm.puntos_totales') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="form-label">Rol</label>
                            <select wire:model="editForm.rol_usuario" class="form-input w-full">
                                <option value="Empleado">Empleado</option>
                                <option value="Administrador">Administrador</option>
                            </select>
                            @error('editForm.rol_usuario') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                        <button type="button" wire:click="closeEditModal" class="btn-secondary">
                            Cancelar
                        </button>
                        <button type="submit" class="btn-primary">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    
    @if($showPointsModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full m-4">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">
                            Ajustar Puntos
                        </h3>
                        <button wire:click="closePointsModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <form wire:submit.prevent="adjustPoints">
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label class="form-label">Tipo de Ajuste</label>
                            <select wire:model="pointsForm.type" class="form-input w-full">
                                <option value="earned">Otorgar Puntos</option>
                                <option value="adjustment">Ajuste Manual</option>
                            </select>
                            @error('pointsForm.type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="form-label">Cantidad de Puntos</label>
                            <input type="number" wire:model="pointsForm.points" class="form-input w-full" min="1" max="10000">
                            @error('pointsForm.points') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="form-label">Descripción</label>
                            <textarea wire:model="pointsForm.description" class="form-input w-full" rows="3" 
                                      placeholder="Ej: Bonificación por desempeño excepcional"></textarea>
                            @error('pointsForm.description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                        <button type="button" wire:click="closePointsModal" class="btn-secondary">
                            Cancelar
                        </button>
                        <button type="submit" class="btn bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                            Ajustar Puntos
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    
    @if (session()->has('message'))
        <div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50" 
             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            {{ session('message') }}
        </div>
    @endif
</div>