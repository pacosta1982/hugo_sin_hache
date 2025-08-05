@extends('layouts.app')

@section('title', 'Reportes y Análisis')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li>
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            </li>
            <li>
                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            </li>
            <li>
                <a href="{{ route('admin.orders') }}" class="hover:text-blue-600">Administración</a>
            </li>
            <li>
                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            </li>
            <li class="text-gray-900 font-medium">Reportes</li>
        </ol>
    </nav>

    
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Reportes y Análisis</h1>
                <p class="text-gray-600">Análisis detallado del sistema de puntos y canjes</p>
            </div>
            <div class="flex space-x-3">
                <div class="relative">
                    <select id="period-filter" class="form-select pr-10">
                        <option value="7">Últimos 7 días</option>
                        <option value="30" selected>Últimos 30 días</option>
                        <option value="90">Últimos 90 días</option>
                        <option value="365">Último año</option>
                        <option value="custom">Personalizado</option>
                    </select>
                </div>
                <button onclick="exportReports()" class="btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Exportar
                </button>
                <button onclick="refreshReports()" class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Actualizar
                </button>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900" id="total-orders">0</div>
                    <div class="text-sm text-gray-600">Total canjes</div>
                    <div class="text-xs text-green-600 mt-1">+12% vs período anterior</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900" id="total-points">0</div>
                    <div class="text-sm text-gray-600">Puntos canjeados</div>
                    <div class="text-xs text-green-600 mt-1">+8% vs período anterior</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900" id="active-users">0</div>
                    <div class="text-sm text-gray-600">Usuarios activos</div>
                    <div class="text-xs text-blue-600 mt-1">+5% vs período anterior</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900" id="completion-rate">0%</div>
                    <div class="text-sm text-gray-600">Tasa de finalización</div>
                    <div class="text-xs text-green-600 mt-1">+3% vs período anterior</div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Canjes en el Tiempo</h3>
                <div class="flex space-x-2">
                    <button class="text-sm text-gray-600 hover:text-gray-900" onclick="changeChart('orders', 'daily')">Diario</button>
                    <button class="text-sm text-blue-600 font-medium" onclick="changeChart('orders', 'weekly')">Semanal</button>
                    <button class="text-sm text-gray-600 hover:text-gray-900" onclick="changeChart('orders', 'monthly')">Mensual</button>
                </div>
            </div>
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                <div class="text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <p class="text-gray-600">Gráfico de canjes por período</p>
                    <p class="text-sm text-gray-500 mt-1">Los datos se cargarán automáticamente</p>
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Productos Más Populares</h3>
                <button class="text-sm text-blue-600 hover:text-blue-700">Ver todos</button>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <span class="text-sm font-bold text-blue-600">1</span>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">Producto Demo 1</div>
                            <div class="text-xs text-gray-500">45 canjes</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-bold text-gray-900">2,250 pts</div>
                        <div class="w-24 bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 75%"></div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <span class="text-sm font-bold text-green-600">2</span>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">Producto Demo 2</div>
                            <div class="text-xs text-gray-500">32 canjes</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-bold text-gray-900">1,600 pts</div>
                        <div class="w-24 bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: 55%"></div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <span class="text-sm font-bold text-yellow-600">3</span>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">Producto Demo 3</div>
                            <div class="text-xs text-gray-500">28 canjes</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-bold text-gray-900">1,400 pts</div>
                        <div class="w-24 bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-600 h-2 rounded-full" style="width: 47%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Actividad de Usuarios</h3>
                <button class="btn-secondary text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Exportar
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Canjes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Puntos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Último Canje</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">Usuario Demo 1</div>
                                <div class="text-sm text-gray-500">usuario1@empresa.com</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">12</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">6,000</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Hace 2 días</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">Usuario Demo 2</div>
                                <div class="text-sm text-gray-500">usuario2@empresa.com</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">8</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">4,200</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Hace 1 semana</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        
        <div class="space-y-6">
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribución de Puntos</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Puntos totales emitidos</span>
                        <span class="text-sm font-bold text-gray-900">125,000</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Puntos canjeados</span>
                        <span class="text-sm font-bold text-red-600">85,000</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Puntos pendientes</span>
                        <span class="text-sm font-bold text-green-600">40,000</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: 68%"></div>
                    </div>
                    <div class="text-xs text-gray-500 text-center">68% de utilización</div>
                </div>
            </div>

            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Estado del Sistema</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Base de datos</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Operativo
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Firebase Auth</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Operativo
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Notificaciones</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Advertencia
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadReportData();
});

function loadReportData() {
    // Simulate loading data
    document.getElementById('total-orders').textContent = '1,234';
    document.getElementById('total-points').textContent = '85,000';
    document.getElementById('active-users').textContent = '156';
    document.getElementById('completion-rate').textContent = '87%';
}

function refreshReports() {
    console.log('Refreshing reports...');
    // Reload the page or fetch new data
    window.location.reload();
}

function exportReports() {
    console.log('Exporting reports...');
    alert('Función de exportación de reportes - por implementar');
}

function changeChart(type, period) {
    console.log(`Change chart: ${type} - ${period}`);
    // Implement chart change functionality
}

// Period filter change handler
document.getElementById('period-filter').addEventListener('change', function() {
    const period = this.value;
    if (period === 'custom') {
        // Show custom date picker
        alert('Selector de fechas personalizado - por implementar');
    } else {
        console.log(`Loading data for period: ${period} days`);
        loadReportData();
    }
});
</script>
@endsection