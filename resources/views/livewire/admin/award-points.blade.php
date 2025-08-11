<div class="space-y-6">
    
    <div class="card-gradient p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Otorgar Puntos</h3>
        
        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit.prevent="awardPoints" class="space-y-4">
            <div class="form-group">
                <label for="employee" class="block text-sm font-medium text-gray-700 mb-2">Empleado</label>
                <select wire:model="selectedEmployeeId" id="employee" class="form-select w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                    <option value="">Seleccionar empleado...</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id_empleado }}">{{ $employee->nombre }} ({{ $employee->puntos_totales }} puntos)</option>
                    @endforeach
                </select>
                @error('selectedEmployeeId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="points" class="block text-sm font-medium text-gray-700 mb-2">Cantidad de Puntos</label>
                <input wire:model="points" type="number" id="points" min="1" max="10000" 
                       class="form-input w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                @error('points') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                <input wire:model="description" type="text" id="description" placeholder="Ej: Bonificación por cumplimiento de meta"
                       class="form-input w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <button type="submit" 
                    class="btn bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-md font-medium transition-colors">
                Otorgar Puntos
            </button>
        </form>
    </div>

    
    <div class="card-gradient p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Transacciones Recientes</h3>
        
        @if(count($recentTransactions) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empleado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Puntos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentTransactions as $transaction)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $transaction->employee->nombre }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($transaction->type === 'earned') bg-green-100 text-green-800 
                                        @elseif($transaction->type === 'spent') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($transaction->type === 'spent')-@endif{{ $transaction->points }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->description }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->admin->nombre ?? 'Sistema' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->created_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500">No hay transacciones recientes.</p>
        @endif
    </div>
</div>
