@extends('layouts.app')

@section('title', 'Panel de Administración')

@section('content')

<div id="admin-auth-check" style="display: none;">
    <div class="min-h-screen flex items-center justify-center">
        <div class="text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-itti-primary mx-auto mb-4"></div>
            <p class="text-gray-600">Verificando permisos de administrador...</p>
        </div>
    </div>
</div>

<div id="admin-content" style="display: none;">
<div class="container mx-auto px-4 py-8 space-y-8">

    
    <div class="relative">
        <div class="bg-gradient-to-r from-purple-50/80 via-indigo-50/80 to-blue-50/60 rounded-3xl p-8 border border-purple-100">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between">
                <div class="flex items-center space-x-6 mb-6 lg:mb-0">
                    
                    <div class="w-20 h-20 bg-gradient-to-br from-purple-600 to-indigo-600 rounded-3xl flex items-center justify-center shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                        </svg>
                    </div>
                    
                    
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">
                            Panel de Administración
                        </h1>
                        <p class="text-lg text-gray-600 mb-3">
                            Control total del sistema UGo
                        </p>
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                Última actualización: {{ now()->format('d/m/Y H:i') }}
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Sistema operativo
                            </span>
                        </div>
                    </div>
                </div>

                
                <div class="flex flex-wrap gap-3">
                    <a href="javascript:void(0)" onclick="goToAdmin('pedidos')" class="btn-primary btn-sm group">
                        <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd"/>
                        </svg>
                        Gestionar Pedidos
                    </a>
                    <a href="javascript:void(0)" onclick="goToAdmin('empleados')" class="btn-secondary btn-sm group">
                        <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                        </svg>
                        Ver Empleados
                    </a>
                    <a href="javascript:void(0)" onclick="goToAdmin('productos')" class="btn-secondary btn-sm group">
                        <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                        </svg>
                        Productos
                    </a>
                </div>
            </div>
        </div>
        
        
        <div class="absolute -top-4 -right-4 w-32 h-32 bg-purple-200/30 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-indigo-200/30 rounded-full blur-3xl"></div>
    </div>

    
    <div class="mobile-card-stack grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="p-8 rounded-3xl shadow-2xl bg-gradient-to-br from-blue-500 via-blue-600 to-cyan-500 text-white relative overflow-hidden transition-all duration-500 hover:scale-105 hover:-translate-y-2 hover:shadow-3xl" style="min-height: 220px;">
            <div class="relative z-10 h-full flex flex-col justify-between">
                <div class="flex items-start justify-between mb-4">
                    <svg class="w-8 h-8 text-white opacity-80" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-2">Total de Pedidos</p>
                    <p class="text-4xl font-bold mb-4">{{ number_format($stats['orders']['total']) }}</p>
                    <div class="flex items-center text-blue-100 text-sm">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        Hoy: {{ $stats['orders']['today'] }}
                    </div>
                </div>
            </div>
        </div>

        
        <div class="p-8 rounded-3xl shadow-2xl bg-gradient-to-br from-green-500 via-green-600 to-emerald-500 text-white relative overflow-hidden transition-all duration-500 hover:scale-105 hover:-translate-y-2 hover:shadow-3xl" style="min-height: 220px;">
            <div class="relative z-10 h-full flex flex-col justify-between">
                <div class="flex items-start justify-between mb-4">
                    <svg class="w-8 h-8 text-white opacity-80" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-green-100 text-sm font-medium mb-2">Empleados</p>
                    <p class="text-4xl font-bold mb-4">{{ number_format($stats['employees']['total']) }}</p>
                    <div class="flex items-center text-green-100 text-sm">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Activos este mes: {{ $stats['employees']['active_this_month'] }}
                    </div>
                </div>
            </div>
        </div>

        
        <div class="p-8 rounded-3xl shadow-2xl bg-gradient-to-br from-purple-500 via-purple-600 to-pink-500 text-white relative overflow-hidden transition-all duration-500 hover:scale-105 hover:-translate-y-2 hover:shadow-3xl" style="min-height: 220px;">
            <div class="relative z-10 h-full flex flex-col justify-between">
                <div class="flex items-start justify-between mb-4">
                    <svg class="w-8 h-8 text-white opacity-80" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <p class="text-purple-100 text-sm font-medium mb-2">Productos Activos</p>
                    <p class="text-4xl font-bold mb-4">{{ number_format($stats['products']['active']) }}</p>
                    <div class="flex items-center text-purple-100 text-sm">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Stock bajo: {{ $stats['products']['low_stock'] }}
                    </div>
                </div>
            </div>
        </div>

        
        <div class="p-8 rounded-3xl shadow-2xl bg-gradient-to-br from-yellow-500 via-orange-500 to-red-500 text-white relative overflow-hidden transition-all duration-500 hover:scale-105 hover:-translate-y-2 hover:shadow-3xl" style="min-height: 220px;">
            <div class="relative z-10 h-full flex flex-col justify-between">
                <div class="flex items-start justify-between mb-4">
                    <svg class="w-8 h-8 text-white opacity-80" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-yellow-100 text-sm font-medium mb-2">Puntos Canjeados</p>
                    <p class="text-4xl font-bold mb-4">{{ number_format($stats['points']['total_redeemed']) }}</p>
                    <div class="flex items-center text-yellow-100 text-sm">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        Este mes: {{ number_format($stats['points']['this_month_redeemed']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
        
        
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Estados de Pedidos</h3>
                            <p class="text-sm text-gray-500">Distribución actual</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.orders') }}" class="btn-ghost btn-sm">Ver todos</a>
                </div>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    
                    <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-xl border border-yellow-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Pendientes</p>
                                <p class="text-sm text-gray-600">Requieren atención</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-yellow-600">{{ $stats['orders']['pending'] }}</p>
                            <div class="w-16 bg-yellow-200 rounded-full h-2 mt-2">
                                <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $stats['orders']['total'] > 0 ? ($stats['orders']['pending'] / $stats['orders']['total']) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="flex items-center justify-between p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">En Proceso</p>
                                <p class="text-sm text-gray-600">Siendo procesados</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-blue-600">{{ $stats['orders']['processing'] }}</p>
                            <div class="w-16 bg-blue-200 rounded-full h-2 mt-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $stats['orders']['total'] > 0 ? ($stats['orders']['processing'] / $stats['orders']['total']) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="flex items-center justify-between p-4 bg-green-50 rounded-xl border border-green-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Completados</p>
                                <p class="text-sm text-gray-600">Finalizados exitosamente</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-green-600">{{ $stats['orders']['completed'] }}</p>
                            <div class="w-16 bg-green-200 rounded-full h-2 mt-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ $stats['orders']['total'] > 0 ? ($stats['orders']['completed'] / $stats['orders']['total']) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="flex items-center justify-between p-4 bg-red-50 rounded-xl border border-red-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Cancelados</p>
                                <p class="text-sm text-gray-600">Pedidos cancelados</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-red-600">{{ $stats['orders']['cancelled'] }}</p>
                            <div class="w-16 bg-red-200 rounded-full h-2 mt-2">
                                <div class="bg-red-500 h-2 rounded-full" style="width: {{ $stats['orders']['total'] > 0 ? ($stats['orders']['cancelled'] / $stats['orders']['total']) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-yellow-600 to-orange-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Análisis de Puntos</h3>
                            <p class="text-sm text-gray-500">Distribución y uso</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.points') }}" class="btn-ghost btn-sm">Ver detalles</a>
                </div>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-2 gap-6">
                    
                    <div class="text-center p-6 bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl border border-green-100">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-green-600 mb-1">{{ number_format($stats['employees']['total_points_distributed']) }}</p>
                        <p class="text-sm text-green-700 font-medium">Puntos Distribuidos</p>
                        <p class="text-xs text-gray-600 mt-2">Total acumulado</p>
                    </div>

                    
                    <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl border border-blue-100">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-blue-600 mb-1">{{ number_format($stats['employees']['total_points_available']) }}</p>
                        <p class="text-sm text-blue-700 font-medium">Puntos Disponibles</p>
                        <p class="text-xs text-gray-600 mt-2">Sin canjear</p>
                    </div>

                    
                    <div class="text-center p-6 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl border border-purple-100">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-purple-600 mb-1">{{ number_format($stats['points']['today_redeemed']) }}</p>
                        <p class="text-sm text-purple-700 font-medium">Canjeados Hoy</p>
                        <p class="text-xs text-gray-600 mt-2">{{ now()->format('d/m/Y') }}</p>
                    </div>

                    
                    <div class="text-center p-6 bg-gradient-to-br from-orange-50 to-yellow-50 rounded-2xl border border-orange-100">
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-yellow-500 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-orange-600 mb-1">{{ number_format($stats['points']['this_week_redeemed']) }}</p>
                        <p class="text-sm text-orange-700 font-medium">Esta Semana</p>
                        <p class="text-xs text-gray-600 mt-2">Últimos 7 días</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="mobile-card-stack grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        
        <div class="card">
            <div class="card-header">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Pedidos Recientes</h3>
                        <p class="text-sm text-gray-500">Últimos 10 pedidos</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    @forelse($recentOrders as $order)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 
                                    {{ $order->estado === 'pending' ? 'bg-yellow-400' : '' }}
                                    {{ $order->estado === 'processing' ? 'bg-blue-400' : '' }}
                                    {{ $order->estado === 'completed' ? 'bg-green-400' : '' }}
                                    {{ $order->estado === 'cancelled' ? 'bg-red-400' : '' }}
                                    rounded-full">
                                </div>
                                <div>
                                    <p class="font-medium text-sm text-gray-900">#{{ $order->id }}</p>
                                    <p class="text-xs text-gray-600">{{ $order->employee->nombre ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ $order->puntos_utilizados }} pts</p>
                                <p class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd"/>
                            </svg>
                            <p>No hay pedidos recientes</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Top Empleados</h3>
                        <p class="text-sm text-gray-500">Por puntos canjeados</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    @forelse($topEmployees->take(5) as $index => $employee)
                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="font-medium text-sm text-gray-900">{{ $employee->nombre }}</p>
                                    <p class="text-xs text-gray-600">{{ $employee->total_orders }} pedidos</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-green-600">{{ number_format($employee->points_redeemed ?? 0) }} pts</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                            </svg>
                            <p>No hay datos de empleados</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Productos Populares</h3>
                        <p class="text-sm text-gray-500">Más canjeados</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    @forelse($popularProducts->take(5) as $index => $product)
                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="font-medium text-sm text-gray-900">{{ Str::limit($product->nombre, 20) }}</p>
                                    <p class="text-xs text-gray-600">{{ $product->costo_puntos }} pts cada uno</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-purple-600">{{ $product->total_orders }} canjes</p>
                                <p class="text-xs text-gray-500">{{ number_format($product->points_earned ?? 0) }} pts total</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                            </svg>
                            <p>No hay productos canjeados</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    
    <div class="relative">
        <div class="bg-gradient-to-br from-gray-50/80 via-white to-indigo-50/30 rounded-3xl p-8 border border-gray-200/40 shadow-xl backdrop-blur-sm">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Acceso Rápido</h3>
                        <p class="text-sm text-gray-600">Herramientas administrativas principales</p>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                <a href="{{ route('admin.orders') }}" class="quick-action-btn">
                    <div class="w-12 h-12 mb-4 mx-auto bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="font-semibold mb-1 leading-tight">Pedidos</span>
                        <span class="text-xs text-gray-500 group-hover:text-white/80 leading-tight">Gestión completa</span>
                    </div>
                </a>
                
                <a href="{{ route('admin.employees') }}" class="quick-action-btn">
                    <div class="w-12 h-12 mb-4 mx-auto bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                        </svg>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="font-semibold mb-1 leading-tight">Empleados</span>
                        <span class="text-xs text-gray-500 group-hover:text-white/80 leading-tight text-center">Usuarios del sistema</span>
                    </div>
                </a>
                
                <a href="{{ route('admin.products') }}" class="quick-action-btn">
                    <div class="w-12 h-12 mb-4 mx-auto bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="font-semibold mb-1 leading-tight">Productos</span>
                        <span class="text-xs text-gray-500 group-hover:text-white/80 leading-tight">Catálogo y stock</span>
                    </div>
                </a>
                
                <a href="{{ route('admin.reports') }}" class="quick-action-btn">
                    <div class="w-12 h-12 mb-4 mx-auto bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                        </svg>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="font-semibold mb-1 leading-tight">Reportes</span>
                        <span class="text-xs text-gray-500 group-hover:text-white/80 leading-tight text-center">Análisis detallado</span>
                    </div>
                </a>
                
                <a href="{{ route('admin.points') }}" class="quick-action-btn">
                    <div class="w-12 h-12 mb-4 mx-auto bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="font-semibold mb-1 leading-tight">Puntos</span>
                        <span class="text-xs text-gray-500 group-hover:text-white/80 leading-tight text-center">Asignación y gestión</span>
                    </div>
                </a>
                
                <a href="{{ route('admin.pwa') }}" class="quick-action-btn">
                    <div class="w-12 h-12 mb-4 mx-auto bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="font-semibold mb-1 leading-tight">PWA</span>
                        <span class="text-xs text-gray-500 group-hover:text-white/80 leading-tight text-center">App Configuration</span>
                    </div>
                </a>
            </div>
        </div>
        
    </div>
</div>

</div> 
</div> 

<style>
.stats-card {
    @apply p-8 rounded-3xl shadow-2xl hover:shadow-3xl transition-all duration-500 transform hover:scale-[1.03] hover:-translate-y-2;
    min-height: 200px;
    background: linear-gradient(135deg, var(--tw-gradient-stops));
    position: relative;
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: 1.5rem;
    padding: 2px;
    background: linear-gradient(135deg, rgba(255,255,255,0.2), rgba(255,255,255,0.05));
    mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    mask-composite: xor;
    -webkit-mask-composite: xor;
}

.quick-action-btn {
    @apply flex flex-col items-center justify-center p-6 bg-gradient-to-br from-white to-gray-50 hover:from-indigo-500 hover:to-purple-600 hover:text-white rounded-2xl transition-all duration-300 text-gray-800 font-medium text-sm text-center group shadow-lg hover:shadow-2xl border border-gray-200 hover:border-transparent;
    min-height: 140px;
    max-width: 180px;
}

.quick-action-btn:hover {
    @apply transform scale-[1.05] -translate-y-1;
}

.quick-action-btn svg {
    @apply transition-all duration-300 group-hover:scale-110 drop-shadow-lg;
}

/* Additional improvements for better visibility */
.mobile-card-stack {
    margin-bottom: 2rem;
}

.card {
    @apply bg-white rounded-3xl shadow-xl border-0;
}

.card-header {
    @apply p-6 border-b border-gray-100/50;
}

.card-body {
    @apply p-6;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add count-up animation to stats
    const statsElements = document.querySelectorAll('.stats-card p[class*="text-3xl"]');
    statsElements.forEach(element => {
        const target = parseInt(element.textContent.replace(/,/g, ''));
        animateCountUp(element, target);
    });
});

function animateCountUp(element, target) {
    const duration = 2000;
    const start = 0;
    const startTime = performance.now();
    
    function updateCount(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const current = Math.floor(start + (target - start) * progress);
        
        element.textContent = current.toLocaleString();
        
        if (progress < 1) {
            requestAnimationFrame(updateCount);
        }
    }
    
    requestAnimationFrame(updateCount);
}

// Admin Authentication Check
document.addEventListener('DOMContentLoaded', function() {
    const authCheck = document.getElementById('admin-auth-check');
    const adminContent = document.getElementById('admin-content');
    
    // Show loading while checking auth
    authCheck.style.display = 'block';
    
    async function checkAdminAuth() {
        const token = localStorage.getItem('firebase_token');
        
        if (!token) {
            // No token, redirect to login
            window.location.href = '/login';
            return;
        }
        
        try {
            // Check if user is admin via API
            const response = await fetch('/api/me', {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) {
                throw new Error('Authentication failed');
            }
            
            const data = await response.json();
            console.log('User data:', data);
            
            if (!data.success || !data.employee) {
                throw new Error('User not found');
            }
            
            // Check if user is admin
            const employee = data.employee;
            const isAdmin = employee.rol_usuario === 'Administrador' || employee.is_admin;
            
            if (!isAdmin) {
                // Not admin, show error
                authCheck.innerHTML = `
                    <div class="min-h-screen flex items-center justify-center">
                        <div class="text-center">
                            <div class="text-red-600 text-6xl mb-4">🚫</div>
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">Acceso Denegado</h1>
                            <p class="text-gray-600 mb-4">No tienes permisos de administrador.</p>
                            <a href="/dashboard" class="btn-primary">Volver al Dashboard</a>
                        </div>
                    </div>
                `;
                return;
            }
            
            // User is admin, show content
            authCheck.style.display = 'none';
            adminContent.style.display = 'block';
            
            // Load admin data dynamically here if needed
            console.log('Admin access granted for:', employee.nombre);
            
        } catch (error) {
            console.error('Admin auth check failed:', error);
            // Redirect to login on error
            window.location.href = '/login';
        }
    }
    
    // Check auth when auth is initialized
    if (window.authInitialized && localStorage.getItem('firebase_token')) {
        setTimeout(checkAdminAuth, 500);
    } else {
        // Wait for auth to initialize
        window.addEventListener('auth-completed', checkAdminAuth);
        
        // Also keep checking periodically in case auth-completed doesn't fire
        let authCheckCount = 0;
        const authInterval = setInterval(() => {
            authCheckCount++;
            if (localStorage.getItem('firebase_token') || authCheckCount > 10) {
                clearInterval(authInterval);
                if (localStorage.getItem('firebase_token')) {
                    checkAdminAuth();
                }
            }
        }, 1000);
    }
});

// Simple admin navigation
window.goToAdmin = function(path) {
    console.log('🔍 Going to admin page:', path);
    const token = localStorage.getItem('firebase_token');
    
    if (!token) {
        console.log('❌ No token, redirecting to login');
        window.location.href = '/login';
        return;
    }
    
    // Just navigate directly - the middleware changes should handle it
    window.location.href = `/admin/${path}`;
};
</script>
@endsection