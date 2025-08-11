<div class="space-y-6">
    {{-- PWA Status Header --}}
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Estado PWA (Progressive Web App)
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Monitoreo y configuración de características PWA
                    </p>
                </div>
                <button wire:click="refreshStatus" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Actualizar
                </button>
            </div>
        </div>
    </div>

    {{-- Installation Status --}}
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h4 class="text-lg font-medium text-gray-900 mb-4">Estado de Instalación</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Installability Status --}}
                <div class="flex items-center p-4 {{ $installationStatus['installable'] ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }} rounded-lg">
                    <div class="flex-shrink-0">
                        @if($installationStatus['installable'])
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @else
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @endif
                    </div>
                    <div class="ml-3">
                        <h5 class="text-sm font-medium {{ $installationStatus['installable'] ? 'text-green-800' : 'text-red-800' }}">
                            {{ $installationStatus['installable'] ? 'PWA Instalable' : 'PWA No Instalable' }}
                        </h5>
                        <p class="text-sm {{ $installationStatus['installable'] ? 'text-green-600' : 'text-red-600' }}">
                            {{ $installationStatus['installable'] ? 'Cumple todos los requisitos' : 'Faltan requisitos' }}
                        </p>
                    </div>
                </div>

                {{-- Requirements Grid --}}
                <div class="space-y-2">
                    @foreach($installationStatus['requirements'] as $requirement => $status)
                        <div class="flex items-center justify-between p-2 rounded {{ $status ? 'bg-green-50' : 'bg-red-50' }}">
                            <span class="text-sm font-medium text-gray-900 capitalize">
                                {{ str_replace('_', ' ', $requirement) }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $status ? 'OK' : 'Falta' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- PWA Analytics --}}
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h4 class="text-lg font-medium text-gray-900 mb-4">Analíticas PWA</h4>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">{{ $pwaAnalytics['total_installations'] ?? 0 }}</div>
                    <div class="text-sm text-blue-600">Instalaciones</div>
                </div>
                
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">{{ $pwaAnalytics['active_users'] ?? 0 }}</div>
                    <div class="text-sm text-green-600">Usuarios Activos</div>
                </div>
                
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600">{{ $pwaAnalytics['offline_usage_percentage'] ?? 0 }}%</div>
                    <div class="text-sm text-purple-600">Uso Offline</div>
                </div>
                
                <div class="text-center p-4 bg-orange-50 rounded-lg">
                    <div class="text-2xl font-bold text-orange-600">{{ $pwaAnalytics['cache_hit_rate'] ?? 0 }}%</div>
                    <div class="text-sm text-orange-600">Cache Hit Rate</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Offline Capabilities --}}
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h4 class="text-lg font-medium text-gray-900 mb-4">Capacidades Offline</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($offlineCapabilities as $capability => $enabled)
                    <div class="flex items-center p-3 {{ $enabled ? 'bg-green-50 border border-green-200' : 'bg-gray-50 border border-gray-200' }} rounded-lg">
                        <div class="flex-shrink-0">
                            @if($enabled)
                                <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            @endif
                        </div>
                        <div class="ml-3">
                            <span class="text-sm font-medium {{ $enabled ? 'text-green-800' : 'text-gray-600' }}">
                                {{ ucfirst(str_replace('_', ' ', $capability)) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- PWA Management Actions --}}
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h4 class="text-lg font-medium text-gray-900 mb-4">Acciones de Administración</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button wire:click="createDefaultIcons" 
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Crear Iconos
                </button>
                
                <button wire:click="regenerateManifest"
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Regenerar Manifest
                </button>
                
                <button wire:click="testPWAFeatures"
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    Probar PWA
                </button>
            </div>
            
            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                <h5 class="text-sm font-medium text-gray-700 mb-2">Información de Debugging</h5>
                <div class="text-xs text-gray-600 font-mono">
                    <div>Manifest: {{ $installationStatus['manifest_exists'] ? '✓ Existe' : '✗ No existe' }}</div>
                    <div>Service Worker: {{ $installationStatus['service_worker_exists'] ? '✓ Registrado' : '✗ No registrado' }}</div>
                    <div>Iconos: {{ $installationStatus['icons_valid'] ? '✓ Válidos' : '✗ Faltantes' }}</div>
                    <div>HTTPS: {{ $installationStatus['requirements']['https'] ? '✓ Seguro' : '✗ No seguro' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Real-time PWA Test Results --}}
    <div x-data="{ testResults: {} }" 
         @pwa-test-results.window="testResults = $event.detail" 
         x-show="Object.keys(testResults).length > 0"
         class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h4 class="text-lg font-medium text-gray-900 mb-4">Resultados de Pruebas PWA</h4>
            
            <div class="space-y-2">
                <template x-for="(result, test) in testResults" :key="test">
                    <div class="flex items-center justify-between p-2 rounded" 
                         :class="result ? 'bg-green-50' : 'bg-red-50'">
                        <span class="text-sm font-medium text-gray-900 capitalize" x-text="test.replace('_', ' ')"></span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                              :class="result ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                              x-text="result ? 'PASS' : 'FAIL'"></span>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>