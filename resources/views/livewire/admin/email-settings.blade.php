<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Configuración de Email</h1>
        <p class="text-gray-600">Gestiona las configuraciones de correo electrónico y plantillas</p>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Estado de Configuración</h2>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Driver de Email</span>
                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded">
                        {{ $emailMetrics['email_driver'] ?? 'No configurado' }}
                    </span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Colas Habilitadas</span>
                    <span class="px-2 py-1 text-xs font-medium {{ ($emailMetrics['queue_enabled'] ?? false) ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} rounded">
                        {{ ($emailMetrics['queue_enabled'] ?? false) ? 'Sí' : 'No' }}
                    </span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Emails Admin Configurados</span>
                    <span class="px-2 py-1 text-xs font-medium {{ ($emailMetrics['admin_emails_configured'] ?? 0) > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} rounded">
                        {{ $emailMetrics['admin_emails_configured'] ?? 0 }}
                    </span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Enviados Hoy</span>
                    <span class="text-sm text-gray-600">{{ $emailMetrics['total_sent_today'] ?? 0 }}</span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Fallos Hoy</span>
                    <span class="text-sm {{ ($emailMetrics['total_failed_today'] ?? 0) > 0 ? 'text-red-600' : 'text-gray-600' }}">
                        {{ $emailMetrics['total_failed_today'] ?? 0 }}
                    </span>
                </div>
            </div>
            
            <button wire:click="refreshMetrics" class="mt-4 w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm font-medium transition-colors">
                Actualizar Métricas
            </button>
        </div>

        
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Probar Configuración</h2>
            
            <div class="space-y-4">
                <div>
                    <label for="testEmail" class="block text-sm font-medium text-gray-700 mb-2">
                        Email de Prueba
                    </label>
                    <input type="email" 
                           wire:model="testEmail" 
                           id="testEmail"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    @error('testEmail') 
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                    @enderror
                </div>
                
                <button wire:click="testEmailConfiguration" 
                        class="w-full bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                    <svg wire:loading wire:target="testEmailConfiguration" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="testEmailConfiguration">Enviar Email de Prueba</span>
                    <span wire:loading wire:target="testEmailConfiguration">Enviando...</span>
                </button>
                
                @if($testResult)
                    <div class="mt-4 p-4 rounded-md {{ $testResult['success'] ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
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
                                    {{ $testResult['success'] ? 'Prueba Exitosa' : 'Prueba Fallida' }}
                                </h3>
                                <p class="mt-1 text-sm {{ $testResult['success'] ? 'text-green-700' : 'text-red-700' }}">
                                    {{ $testResult['message'] }}
                                </p>
                                
                                @if(isset($testResult['configuration']))
                                    <div class="mt-2 text-xs {{ $testResult['success'] ? 'text-green-600' : 'text-red-600' }}">
                                        <strong>Driver:</strong> {{ $testResult['configuration']['driver'] ?? 'N/A' }}<br>
                                        <strong>Host:</strong> {{ $testResult['configuration']['host'] ?? 'N/A' }}<br>
                                        <strong>Puerto:</strong> {{ $testResult['configuration']['port'] ?? 'N/A' }}<br>
                                        <strong>From:</strong> {{ $testResult['configuration']['from_address'] ?? 'N/A' }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Plantillas de Email Disponibles</h2>
            <p class="text-sm text-gray-600">Plantillas configuradas en el sistema</p>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-gray-900">Confirmación de Pedido</h3>
                            <p class="text-xs text-gray-500">OrderConfirmationMail</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">Se envía automáticamente cuando un empleado realiza un canje exitoso.</p>
                </div>

                
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-gray-900">Actualización de Estado</h3>
                            <p class="text-xs text-gray-500">OrderStatusUpdateMail</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">Notifica cambios en el estado de las órdenes (procesando, completado, cancelado).</p>
                </div>

                
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <div class="h-8 w-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="h-4 w-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-gray-900">Puntos Otorgados</h3>
                            <p class="text-xs text-gray-500">PointsAwardedMail</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">Incluye recomendaciones personalizadas cuando un empleado recibe puntos.</p>
                </div>

                
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <div class="h-8 w-8 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="h-4 w-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-gray-900">Alerta de Stock Bajo</h3>
                            <p class="text-xs text-gray-500">LowStockAlertMail</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">Alerta automática a administradores sobre productos con stock bajo o agotados.</p>
                </div>

                
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <div class="h-8 w-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <svg class="h-4 w-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 12H6L5 9z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-gray-900">Notificación Admin</h3>
                            <p class="text-xs text-gray-500">OrderNotificationMail</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">Notifica a administradores sobre nuevas órdenes que requieren procesamiento.</p>
                </div>

                
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <div class="h-8 w-8 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-gray-900">Layout Base</h3>
                            <p class="text-xs text-gray-500">emails.layout</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">Plantilla base con diseño corporativo UGo para todos los emails.</p>
                </div>
            </div>
        </div>
    </div>
</div>
