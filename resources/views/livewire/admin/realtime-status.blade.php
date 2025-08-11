<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Estado de Tiempo Real</h1>
        <p class="text-gray-600">Monitorea conexiones WebSocket y actualizaciones en vivo</p>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Configuración WebSocket</h2>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Estado</span>
                    <span class="px-2 py-1 text-xs font-medium rounded {{ ($realtimeConfig['enabled'] ?? false) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ($realtimeConfig['enabled'] ?? false) ? 'Habilitado' : 'Deshabilitado' }}
                    </span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Driver</span>
                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded">
                        {{ $realtimeConfig['driver'] ?? 'No configurado' }}
                    </span>
                </div>
                
                @if(isset($realtimeConfig['pusher']['cluster']))
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Cluster Pusher</span>
                    <span class="text-sm text-gray-600">{{ $realtimeConfig['pusher']['cluster'] }}</span>
                </div>
                @endif
                
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Intervalo Polling</span>
                    <span class="text-sm text-gray-600">{{ ($realtimeConfig['fallback_polling_interval'] ?? 30000) / 1000 }}s</span>
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Prueba de Conexión</h2>
            
            <div class="space-y-4">
                <button wire:click="testRealtimeConnection" 
                        class="w-full bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                    <svg wire:loading wire:target="testRealtimeConnection" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="testRealtimeConnection">Probar WebSocket</span>
                    <span wire:loading wire:target="testRealtimeConnection">Probando...</span>
                </button>
                
                @if($testResult)
                    <div class="p-4 rounded-md {{ $testResult['success'] ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                @if($testResult['success'])
                                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                @else
                                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                @endif
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium {{ $testResult['success'] ? 'text-green-800' : 'text-red-800' }}">
                                    {{ $testResult['success'] ? 'Conexión Exitosa' : 'Conexión Fallida' }}
                                </h3>
                                <p class="mt-1 text-sm {{ $testResult['success'] ? 'text-green-700' : 'text-red-700' }}">
                                    {{ $testResult['message'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <button wire:click="refreshStatus" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Actualizar Estado
                </button>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Eventos en Tiempo Real</h2>
            <p class="text-sm text-gray-600">Eventos configurados para transmisión</p>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 12H6L5 9z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-gray-900">Órdenes</h3>
                            <p class="text-xs text-gray-500">Canales: admin.orders, employee.{id}</p>
                        </div>
                    </div>
                    <ul class="text-xs text-gray-600 space-y-1">
                        <li>• order.created - Nueva orden</li>
                        <li>• order.status.updated - Estado actualizado</li>
                    </ul>
                </div>

                
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <div class="h-8 w-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="h-4 w-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-gray-900">Puntos</h3>
                            <p class="text-xs text-gray-500">Canales: admin.points, employee.{id}</p>
                        </div>
                    </div>
                    <ul class="text-xs text-gray-600 space-y-1">
                        <li>• points.awarded - Puntos otorgados</li>
                        <li>• points.deducted - Puntos canjeados</li>
                    </ul>
                </div>

                
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <div class="h-8 w-8 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="h-4 w-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-gray-900">Inventario</h3>
                            <p class="text-xs text-gray-500">Canal: admin.inventory</p>
                        </div>
                    </div>
                    <ul class="text-xs text-gray-600 space-y-1">
                        <li>• inventory.alert - Stock bajo</li>
                        <li>• inventory.depleted - Agotado</li>
                    </ul>
                </div>

                
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-gray-900">Conexión</h3>
                            <p class="text-xs text-gray-500">Estado de WebSocket</p>
                        </div>
                    </div>
                    <div class="space-y-1" x-data="{ status: 'disconnected' }" x-init="
                        if (window.realtimeService) {
                            status = window.realtimeService.getStatus().connected ? 'connected' : 'disconnected';
                            window.realtimeService.on('connected', () => status = 'connected');
                            window.realtimeService.on('disconnected', () => status = 'disconnected');
                        }
                    ">
                        <div class="flex items-center space-x-2">
                            <div class="h-2 w-2 rounded-full" :class="status === 'connected' ? 'bg-green-500' : 'bg-red-500'"></div>
                            <span class="text-xs text-gray-600" x-text="status === 'connected' ? 'Conectado' : 'Desconectado'"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
