<div class="space-y-6">
    {{-- Cache Management Header --}}
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Administración de Caché
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Monitoreo y gestión del sistema de caché
                        @if($lastRefresh)
                            • Actualizado: {{ $lastRefresh }}
                        @endif
                    </p>
                </div>
                <button wire:click="refreshStats" 
                        wire:loading.attr="disabled" 
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span wire:loading.remove>Actualizar</span>
                    <span wire:loading>Cargando...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Cache Statistics --}}
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h4 class="text-lg font-medium text-gray-900 mb-4">Estadísticas de Caché</h4>
            
            @if(!empty($advancedStats))
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">{{ $advancedStats['driver'] ?? 'N/A' }}</div>
                        <div class="text-sm text-blue-600">Driver</div>
                    </div>
                    
                    @if(isset($advancedStats['hit_rate']))
                        <div class="text-center p-4 {{ $advancedStats['hit_rate'] >= 80 ? 'bg-green-50' : ($advancedStats['hit_rate'] >= 60 ? 'bg-yellow-50' : 'bg-red-50') }} rounded-lg">
                            <div class="text-2xl font-bold {{ $advancedStats['hit_rate'] >= 80 ? 'text-green-600' : ($advancedStats['hit_rate'] >= 60 ? 'text-yellow-600' : 'text-red-600') }}">{{ $advancedStats['hit_rate'] }}%</div>
                            <div class="text-sm {{ $advancedStats['hit_rate'] >= 80 ? 'text-green-600' : ($advancedStats['hit_rate'] >= 60 ? 'text-yellow-600' : 'text-red-600') }}">Hit Rate</div>
                        </div>
                    @endif
                    
                    @if(isset($advancedStats['memory_used']))
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">{{ $advancedStats['memory_used'] }}</div>
                            <div class="text-sm text-purple-600">Memoria Usada</div>
                        </div>
                    @endif
                    
                    @if(isset($advancedStats['total_keys']))
                        <div class="text-center p-4 bg-orange-50 rounded-lg">
                            <div class="text-2xl font-bold text-orange-600">{{ number_format($advancedStats['total_keys']) }}</div>
                            <div class="text-sm text-orange-600">Total Keys</div>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Basic Cache Status --}}
            @if(!empty($cacheStats))
                <div class="mt-6">
                    <h5 class="text-md font-medium text-gray-700 mb-3">Estado de Cachés Específicas</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($cacheStats as $key => $status)
                            <div class="flex items-center justify-between p-3 {{ $status['exists'] ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }} rounded-lg">
                                <span class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace(['_', '.'], [' ', ' '], $key)) }}</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $status['exists'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $status['exists'] ? 'Cached' : 'Missing' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Redis Advanced Metrics --}}
            @if(isset($advancedStats['hits']) && isset($advancedStats['misses']))
                <div class="mt-6">
                    <h5 class="text-md font-medium text-gray-700 mb-3">Métricas Redis</h5>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-lg font-semibold text-gray-700">{{ number_format($advancedStats['hits']) }}</div>
                            <div class="text-xs text-gray-600">Hits</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <div class="text-lg font-semibold text-gray-700">{{ number_format($advancedStats['misses']) }}</div>
                            <div class="text-xs text-gray-600">Misses</div>
                        </div>
                        @if(isset($advancedStats['evicted_keys']))
                            <div class="text-center p-3 bg-gray-50 rounded-lg">
                                <div class="text-lg font-semibold text-gray-700">{{ number_format($advancedStats['evicted_keys']) }}</div>
                                <div class="text-xs text-gray-600">Evicted</div>
                            </div>
                        @endif
                        @if(isset($advancedStats['expired_keys']))
                            <div class="text-center p-3 bg-gray-50 rounded-lg">
                                <div class="text-lg font-semibold text-gray-700">{{ number_format($advancedStats['expired_keys']) }}</div>
                                <div class="text-xs text-gray-600">Expired</div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Cache Management Actions --}}
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h4 class="text-lg font-medium text-gray-900 mb-4">Acciones de Gestión</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                {{-- Selective Cache Clearing --}}
                <div class="space-y-2">
                    <h5 class="text-sm font-medium text-gray-700">Limpiar Caché Específica</h5>
                    <button wire:click="clearProductCache" 
                            class="w-full inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Productos
                    </button>
                    <button wire:click="clearEmployeeCache"
                            class="w-full inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Empleados
                    </button>
                    <button wire:click="clearOrderCache"
                            class="w-full inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        Pedidos
                    </button>
                </div>

                {{-- Cache Operations --}}
                <div class="space-y-2">
                    <h5 class="text-sm font-medium text-gray-700">Operaciones de Caché</h5>
                    <button wire:click="warmUpCache"
                            class="w-full inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Precargar Caché
                    </button>
                    <button wire:click="logCacheMetrics"
                            class="w-full inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Log Métricas
                    </button>
                </div>

                {{-- Critical Actions --}}
                <div class="space-y-2">
                    <h5 class="text-sm font-medium text-gray-700">Acciones Críticas</h5>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                        <p class="text-xs text-red-700 mb-2">⚠️ Esta acción limpiará toda la caché del sistema</p>
                        <button wire:click="clearAllCache" 
                                wire:confirm="¿Estás seguro de que quieres limpiar toda la caché? Esta acción puede afectar el rendimiento temporalmente."
                                class="w-full inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Limpiar Todo
                        </button>
                    </div>
                </div>
            </div>

            {{-- Performance Recommendations --}}
            @if(isset($advancedStats['hit_rate']))
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h5 class="text-sm font-medium text-gray-700 mb-2">Recomendaciones de Rendimiento</h5>
                    <div class="text-sm text-gray-600 space-y-1">
                        @if($advancedStats['hit_rate'] < 70)
                            <div class="flex items-start">
                                <svg class="w-4 h-4 text-yellow-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>El hit rate está por debajo del 70%. Considera precargar más datos críticos.</span>
                            </div>
                        @endif
                        
                        @if(isset($advancedStats['evicted_keys']) && $advancedStats['evicted_keys'] > 100)
                            <div class="flex items-start">
                                <svg class="w-4 h-4 text-orange-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>Se están expulsando muchas keys ({{ number_format($advancedStats['evicted_keys']) }}). Considera aumentar la memoria de Redis.</span>
                            </div>
                        @endif
                        
                        @if($advancedStats['hit_rate'] >= 80)
                            <div class="flex items-start">
                                <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Excelente hit rate ({{ $advancedStats['hit_rate'] }}%). El sistema de caché está funcionando óptimamente.</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
