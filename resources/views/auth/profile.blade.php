@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="container mx-auto px-4 py-8 space-y-8">
    
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm">
            <li>
                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-itti-primary transition-colors">Dashboard</a>
            </li>
            <li>
                <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            </li>
            <li class="text-gray-900 font-medium">Mi Perfil</li>
        </ol>
    </nav>

    <div class="relative">
        <div class="bg-gradient-to-r from-itti-primary/10 via-itti-secondary/10 to-itti-primary/5 rounded-3xl p-8 border border-itti-primary/20">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between">
                <div class="flex items-center space-x-6 mb-6 lg:mb-0">
                    <div class="relative">
                        <div class="w-24 h-24 bg-gradient-to-br from-itti-primary to-itti-secondary rounded-3xl flex items-center justify-center shadow-lg">
                            <span id="profile-avatar-initial" class="text-3xl font-bold text-white">U</span>
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 rounded-full border-4 border-white flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    
                    <div>
                        <h1 id="profile-header-name" class="text-3xl font-bold text-gray-900 mb-2">Usuario</h1>
                        <p id="profile-header-cargo" class="text-lg text-gray-600 mb-2">Cargo no especificado</p>
                        <div class="flex items-center space-x-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $employee && $employee->is_admin ? 'bg-gradient-to-r from-purple-100 to-purple-200 text-purple-800' : 'bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800' }}">
                                @if($employee && $employee->is_admin)
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Administrador
                                @else
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    Usuario
                                @endif
                            </span>
                            <span class="text-sm text-gray-500">
                                Miembro desde {{ $employee ? $employee->created_at->format('M Y') : 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 lg:gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-white/50 rounded-2xl flex items-center justify-center mx-auto mb-2">
                            <svg class="w-8 h-8 text-itti-primary" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <p id="profile-total-points" class="text-2xl font-bold text-gray-900">Cargando...</p>
                        <p class="text-xs text-gray-600">Puntos Totales</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-white/50 rounded-2xl flex items-center justify-center mx-auto mb-2">
                            <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <p id="profile-orders-count" class="text-2xl font-bold text-gray-900">0</p>
                        <p class="text-xs text-gray-600">Canjes</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-white/50 rounded-2xl flex items-center justify-center mx-auto mb-2">
                            <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <p id="profile-favorites-count" class="text-2xl font-bold text-gray-900">0</p>
                        <p class="text-xs text-gray-600">Favoritos</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="absolute -top-4 -right-4 w-32 h-32 bg-itti-secondary/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-itti-primary/10 rounded-full blur-3xl"></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-8">
            
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-itti-primary to-itti-secondary rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Información Personal</h2>
                                <p class="text-sm text-gray-500">Gestiona tu perfil y datos personales</p>
                            </div>
                        </div>
                        <button onclick="enableEdit()" id="edit-btn" class="btn-secondary btn-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <form id="profile-form" onsubmit="saveProfile(event)">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="nombre" class="form-label flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-itti-primary" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    Nombre completo
                                </label>
                                <input 
                                    type="text" 
                                    id="nombre" 
                                    name="nombre" 
                                    value="Cargando..." 
                                    class="form-input" 
                                    disabled>
                            </div>
                            
                            <div class="form-group">
                                <label for="email" class="form-label flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-itti-primary" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                    Correo electrónico
                                </label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    value="Cargando..." 
                                    class="form-input bg-gray-100 cursor-not-allowed" 
                                    disabled 
                                    readonly>
                            </div>
                            
                            <div class="form-group">
                                <label for="departamento" class="form-label flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-itti-primary" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h12v8H4V6z" clip-rule="evenodd"/>
                                    </svg>
                                    Departamento
                                </label>
                                <input 
                                    type="text" 
                                    id="departamento" 
                                    name="departamento" 
                                    value="{{ $employee ? $employee->departamento : '' }}" 
                                    class="form-input" 
                                    disabled>
                            </div>
                            
                            <div class="form-group">
                                <label for="cargo" class="form-label flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-itti-primary" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Cargo
                                </label>
                                <input 
                                    type="text" 
                                    id="cargo" 
                                    name="cargo" 
                                    value="{{ $employee ? $employee->cargo : '' }}" 
                                    class="form-input" 
                                    disabled>
                            </div>
                        </div>

                        <div class="mt-8 hidden fade-in" id="save-actions">
                            <div class="flex flex-col sm:flex-row gap-4">
                                <button type="submit" class="btn-primary flex-1 sm:flex-none">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Guardar cambios
                                </button>
                                <button type="button" onclick="cancelEdit()" class="btn-secondary flex-1 sm:flex-none">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Cancelar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Resumen de Actividad</h2>
                            <p class="text-sm text-gray-500">Tu historial de uso y estadísticas</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div class="stats-card bg-gradient-to-br from-blue-50 to-indigo-50 border-blue-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p id="activity-orders-count" class="text-3xl font-bold text-gray-900">0</p>
                                    <p class="text-sm text-gray-600">Canjes realizados</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="stats-card bg-gradient-to-br from-green-50 to-emerald-50 border-green-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p id="activity-points-total" class="text-3xl font-bold text-gray-900">0</p>
                                    <p class="text-sm text-gray-600">Puntos ganados</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="stats-card bg-gradient-to-br from-red-50 to-pink-50 border-red-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p id="activity-favorites-count" class="text-3xl font-bold text-gray-900">0</p>
                                    <p class="text-sm text-gray-600">Productos favoritos</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-itti-primary to-itti-secondary rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Mis Puntos</h3>
                            <p class="text-sm text-gray-500">Balance y historial</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body space-y-4">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-itti-primary/5 to-itti-secondary/5 rounded-2xl border border-itti-primary/10">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gradient-to-r from-itti-primary to-itti-secondary rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Puntos totales</span>
                            </div>
                            <span id="points-total" class="text-xl font-bold text-gray-900">0</span>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-red-50 to-pink-50 rounded-2xl border border-red-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gradient-to-r from-red-500 to-pink-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Puntos canjeados</span>
                            </div>
                            <span id="points-redeemed" class="text-lg font-bold text-red-600">0</span>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl border border-green-200">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900">Disponibles</span>
                                </div>
                                <span id="points-available" class="text-2xl font-bold text-green-600">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-gray-600 to-gray-800 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Información de Cuenta</h3>
                            <p class="text-sm text-gray-500">Detalles del perfil</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <dl class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <dt class="text-sm font-medium text-gray-600 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zM8 6a2 2 0 114 0v1H8V6z" clip-rule="evenodd"/>
                                </svg>
                                ID de empleado
                            </dt>
                            <dd id="employee-id" class="text-sm text-gray-900 font-mono bg-white px-2 py-1 rounded border">Cargando...</dd>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <dt class="text-sm font-medium text-gray-600 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Rol de usuario
                            </dt>
                            <dd class="text-sm">
                                <span id="user-role" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800">
                                    Usuario
                                </span>
                            </dd>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <dt class="text-sm font-medium text-gray-600 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                Miembro desde
                            </dt>
                            <dd id="member-since" class="text-sm text-gray-900">Cargando...</dd>
                        </div>
                        
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <dt class="text-sm font-medium text-gray-600 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                Última actividad
                            </dt>
                            <dd id="last-activity" class="text-sm text-gray-900">Cargando...</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Acciones Rápidas</h3>
                            <p class="text-sm text-gray-500">Atajos y navegación</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body space-y-3">
                    <a href="{{ route('products.index') }}" class="w-full btn-primary group">
                        <div class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            Explorar productos
                        </div>
                    </a>
                    
                    <a href="{{ route('orders.history') }}" class="w-full btn-secondary group">
                        <div class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Mi historial
                        </div>
                    </a>
                    
                    <a href="{{ route('favorites.index') }}" class="w-full btn-ghost group">
                        <div class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            Mis favoritos
                        </div>
                    </a>
                    
                    <div class="border-t border-gray-200 my-4"></div>
                    
                    <button onclick="window.location.reload()" class="w-full btn-ghost text-sm group">
                        <div class="flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2 group-hover:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Actualizar datos
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let originalData = {};

function enableEdit() {
    const inputs = document.querySelectorAll('#profile-form input');
    const editBtn = document.getElementById('edit-btn');
    const saveActions = document.getElementById('save-actions');
    
    // Store original data and enable editing for editable fields only
    inputs.forEach(input => {
        originalData[input.name] = input.value;
        
        // Email field should never be editable
        if (input.name === 'email') {
            return; // Skip email field
        }
        
        input.disabled = false;
        input.classList.remove('cursor-not-allowed', 'bg-gray-50');
        input.classList.add('focus:ring-2', 'focus:ring-itti-primary', 'focus:border-itti-primary');
    });
    
    // Smooth transitions
    editBtn.classList.add('hidden', 'animate-fade-out');
    saveActions.classList.remove('hidden');
    saveActions.classList.add('animate-fade-in');
    
    // Add edit mode styling to form
    const form = document.getElementById('profile-form');
    form.classList.add('edit-mode');
}

function cancelEdit() {
    const inputs = document.querySelectorAll('#profile-form input');
    const editBtn = document.getElementById('edit-btn');
    const saveActions = document.getElementById('save-actions');
    
    // Restore original data
    inputs.forEach(input => {
        input.value = originalData[input.name];
        input.disabled = true;
        
        // Keep email field with special readonly styling
        if (input.name === 'email') {
            input.classList.add('cursor-not-allowed', 'bg-gray-100');
        } else {
            input.classList.add('cursor-not-allowed', 'bg-gray-50');
        }
        
        input.classList.remove('focus:ring-2', 'focus:ring-itti-primary', 'focus:border-itti-primary');
    });
    
    // Smooth transitions
    editBtn.classList.remove('hidden', 'animate-fade-out');
    editBtn.classList.add('animate-fade-in');
    saveActions.classList.add('hidden');
    saveActions.classList.remove('animate-fade-in');
    
    // Remove edit mode styling
    const form = document.getElementById('profile-form');
    form.classList.remove('edit-mode');
}

async function saveProfile(event) {
    event.preventDefault();
    
    const form = document.getElementById('profile-form');
    const saveBtn = form.querySelector('button[type="submit"]');
    const originalText = saveBtn.innerHTML;
    
    // Show loading state with improved animation
    saveBtn.disabled = true;
    saveBtn.innerHTML = `
        <div class="inline-flex items-center">
            <div class="animate-spin rounded-full h-4 w-4 border-2 border-white/20 border-t-white mr-2"></div>
            Guardando...
        </div>
    `;
    
    try {
        const formData = new FormData(form);
        
        const response = await fetch(`{{ route('profile.update') }}`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('firebase_token')}`,
                'X-CSRF-TOKEN': formData.get('_token'),
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Use modern toast notification
            if (window.showToast) {
                window.showToast('Perfil actualizado exitosamente', 'success');
            } else {
                console.log('Profile updated successfully');
            }
            
            // Disable inputs and hide save actions with animation
            const inputs = document.querySelectorAll('#profile-form input');
            const editBtn = document.getElementById('edit-btn');
            const saveActions = document.getElementById('save-actions');
            
            inputs.forEach(input => {
                input.disabled = true;
                
                // Keep email field with special readonly styling
                if (input.name === 'email') {
                    input.classList.add('cursor-not-allowed', 'bg-gray-100');
                } else {
                    input.classList.add('cursor-not-allowed', 'bg-gray-50');
                }
                
                input.classList.remove('focus:ring-2', 'focus:ring-itti-primary', 'focus:border-itti-primary');
            });
            
            // Smooth transitions back to view mode
            editBtn.classList.remove('hidden');
            editBtn.classList.add('animate-fade-in');
            saveActions.classList.add('hidden');
            
            // Remove edit mode styling
            form.classList.remove('edit-mode');
            
        } else {
            if (window.showToast) {
                window.showToast(data.message || 'Error al actualizar el perfil', 'error');
            } else {
                console.error('Profile update failed:', data.message);
            }
        }
    } catch (error) {
        console.error('Profile update error:', error);
        if (window.showToast) {
            window.showToast('Error de conexión. Inténtalo de nuevo.', 'error');
        } else {
            console.error('Network error during profile update');
        }
    } finally {
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalText;
    }
}

// Load profile stats after authentication
async function loadProfileStats() {
    try {
        const token = localStorage.getItem('firebase_token');
        if (!token) {
            console.log('No Firebase token available');
            return;
        }

        console.log('Loading profile stats...');
        
        // Load employee data
        const employeeResponse = await fetch('/api/me', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        if (!employeeResponse.ok) {
            throw new Error(`Employee API error: ${employeeResponse.status}`);
        }

        const employeeData = await employeeResponse.json();
        if (!employeeData.success || !employeeData.employee) {
            throw new Error('Invalid employee data response');
        }

        const employee = employeeData.employee;
        const firebaseUser = employeeData.firebase_user;
        console.log('Employee data loaded:', employee);
        console.log('Firebase user data loaded:', firebaseUser);

        // Update profile form with Firebase user data
        updateProfileForm(firebaseUser, employee);

        // Update profile stats
        updateProfileStats(employee);

        // Load orders count
        const ordersResponse = await fetch('/api/orders/history', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        if (ordersResponse.ok) {
            const ordersData = await ordersResponse.json();
            const ordersCount = ordersData.orders?.length || 0;
            updateOrdersCounts(ordersCount);
        }

        // Load favorites count  
        const favoritesResponse = await fetch('/api/favoritos', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        if (favoritesResponse.ok) {
            const favoritesData = await favoritesResponse.json();
            const favoritesCount = favoritesData.favorites?.length || 0;
            updateFavoritesCounts(favoritesCount);
        }

    } catch (error) {
        console.error('Error loading profile stats:', error);
    }
}

function updateProfileForm(firebaseUser, employee) {
    // Get display name with Spanish naming convention
    let displayName = firebaseUser?.name || firebaseUser?.displayName || '';
    
    // If no display name, try to extract from email
    if (!displayName && firebaseUser?.email) {
        const emailParts = firebaseUser.email.split('@')[0];
        // Convert email prefix to display name (e.g., "john.doe" -> "John Doe")
        displayName = emailParts.split('.').map(part => 
            part.charAt(0).toUpperCase() + part.slice(1)
        ).join(' ');
    }
    
    // Parse name for Spanish naming convention
    let fullName = displayName || 'Usuario';
    let firstName = displayName ? displayName.split(' ')[0] : 'Usuario';
    let initials = firstName.charAt(0).toUpperCase();
    
    if (displayName) {
        const names = displayName.trim().split(' ');
        firstName = names[0];
        
        // Apply Spanish naming convention for display name
        let lastName = '';
        if (names.length >= 4) {
            // 4+ names: assume [First] [Middle(s)] [Paternal] [Maternal]
            lastName = names[names.length - 2]; // Paternal surname (second to last)
        } else if (names.length >= 2) {
            // 2-3 names: use second name as surname
            lastName = names[1];
        }
        
        fullName = firstName + (lastName ? ' ' + lastName : '');
        initials = firstName.charAt(0).toUpperCase();
    }

    // Update profile header card
    const profileAvatarInitial = document.getElementById('profile-avatar-initial');
    const profileHeaderName = document.getElementById('profile-header-name');
    const profileHeaderCargo = document.getElementById('profile-header-cargo');
    
    if (profileAvatarInitial) profileAvatarInitial.textContent = initials;
    if (profileHeaderName) profileHeaderName.textContent = fullName;
    if (profileHeaderCargo) profileHeaderCargo.textContent = employee?.cargo || 'Empleado';

    // Update form fields
    const nameField = document.getElementById('nombre');
    if (nameField) {
        nameField.value = displayName || 'Usuario';
    }

    // Update email field with Firebase email (always readonly)
    const emailField = document.getElementById('email');
    if (emailField) {
        emailField.value = firebaseUser?.email || '';
    }

    // Update other employee fields
    const departamentoField = document.getElementById('departamento');
    if (departamentoField) {
        departamentoField.value = employee?.departamento || '';
    }

    const cargoField = document.getElementById('cargo');
    if (cargoField) {
        cargoField.value = employee?.cargo || '';
    }

    // Update account information fields
    const employeeIdField = document.getElementById('employee-id');
    if (employeeIdField) {
        if (employee?.id_empleado) {
            // Create a short, user-friendly ID from the full employee ID
            let shortId = employee.id_empleado;
            
            // If it's a long UUID or similar, take first 8 characters
            if (shortId.length > 12) {
                shortId = shortId.substring(0, 8).toUpperCase();
            }
            // If it's a long number, take last 6 digits
            else if (/^\d+$/.test(shortId) && shortId.length > 6) {
                shortId = '#' + shortId.slice(-6);
            }
            // If it contains dashes (UUID), take first segment
            else if (shortId.includes('-')) {
                shortId = shortId.split('-')[0].toUpperCase();
            }
            
            employeeIdField.textContent = shortId;
        } else {
            employeeIdField.textContent = 'N/A';
        }
    }

    const userRoleField = document.getElementById('user-role');
    if (userRoleField && employee) {
        if (employee.is_admin || employee.rol_usuario === 'Administrador') {
            userRoleField.textContent = 'Administrador';
            userRoleField.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-purple-100 to-purple-200 text-purple-800';
        } else {
            userRoleField.textContent = 'Usuario';
            userRoleField.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800';
        }
    }

    const memberSinceField = document.getElementById('member-since');
    if (memberSinceField) {
        if (employee?.created_at) {
            // Format date for Spanish locale
            const createdDate = new Date(employee.created_at);
            const formattedDate = createdDate.toLocaleDateString('es-ES', {
                day: '2-digit',
                month: '2-digit', 
                year: 'numeric'
            });
            memberSinceField.textContent = formattedDate;
        } else if (firebaseUser?.metadata?.creationTime) {
            // Use Firebase creation time as fallback
            const createdDate = new Date(firebaseUser.metadata.creationTime);
            const formattedDate = createdDate.toLocaleDateString('es-ES', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
            memberSinceField.textContent = formattedDate;
        } else {
            memberSinceField.textContent = 'N/A';
        }
    }

    const lastActivityField = document.getElementById('last-activity');
    if (lastActivityField) {
        if (employee?.updated_at) {
            const updatedDate = new Date(employee.updated_at);
            const formattedDate = updatedDate.toLocaleDateString('es-ES', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            lastActivityField.textContent = formattedDate;
        } else if (firebaseUser?.metadata?.lastSignInTime) {
            // Use Firebase last sign in time as fallback
            const lastSignIn = new Date(firebaseUser.metadata.lastSignInTime);
            const formattedDate = lastSignIn.toLocaleDateString('es-ES', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            lastActivityField.textContent = formattedDate;
        } else {
            lastActivityField.textContent = 'N/A';
        }
    }
}

function updateProfileStats(employee) {
    const availablePoints = employee.puntos_totales || 0; // Current available balance
    const redeemedPoints = employee.puntos_canjeados || 0; // Historical total redeemed
    const totalEarnedPoints = availablePoints + redeemedPoints; // Total earned over time

    // Update all points displays
    document.getElementById('profile-total-points').textContent = availablePoints.toLocaleString();
    document.getElementById('activity-points-total').textContent = totalEarnedPoints.toLocaleString();
    document.getElementById('points-total').textContent = availablePoints.toLocaleString();
    document.getElementById('points-redeemed').textContent = redeemedPoints.toLocaleString();
    document.getElementById('points-available').textContent = availablePoints.toLocaleString();
}

function updateOrdersCounts(count) {
    document.getElementById('profile-orders-count').textContent = count;
    document.getElementById('activity-orders-count').textContent = count;
}

function updateFavoritesCounts(count) {
    document.getElementById('profile-favorites-count').textContent = count;
    document.getElementById('activity-favorites-count').textContent = count;
}

// Listen for auth completion
window.addEventListener('auth-completed', (event) => {
    console.log('Profile: Auth completed, loading stats');
    setTimeout(() => {
        loadProfileStats();
    }, 100);
});

// Check if auth is already completed
if (window.authInitialized && localStorage.getItem('firebase_token')) {
    console.log('Profile: Auth already initialized, loading stats');
    setTimeout(() => {
        loadProfileStats();
    }, 100);
}

// Add smooth scrolling and fade-in animations on page load
document.addEventListener('DOMContentLoaded', function() {
    // Add fade-in animation to cards
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Add hover effects to quick action buttons
    const quickActionBtns = document.querySelectorAll('.card-body a, .card-body button');
    quickActionBtns.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Initialize disabled input styling
    const inputs = document.querySelectorAll('#profile-form input[disabled]');
    inputs.forEach(input => {
        if (input.name === 'email') {
            input.classList.add('cursor-not-allowed', 'bg-gray-100');
        } else {
            input.classList.add('cursor-not-allowed', 'bg-gray-50');
        }
    });
});

// Add custom CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes fadeOut {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-10px); }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out forwards;
    }
    
    .animate-fade-out {
        animation: fadeOut 0.3s ease-out forwards;
    }
    
    .edit-mode {
        background: linear-gradient(135deg, rgba(10, 221, 144, 0.02) 0%, rgba(0, 174, 110, 0.02) 100%);
        border-radius: 1rem;
        padding: 1rem;
        margin: -1rem;
        border: 1px solid rgba(10, 221, 144, 0.1);
    }
    
    .card {
        transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .btn-primary:hover, .btn-secondary:hover, .btn-ghost:hover {
        transform: translateY(-1px);
    }
    
    .stats-card {
        transition: transform 0.2s ease-out;
    }
    
    .stats-card:hover {
        transform: scale(1.02);
    }
`;
document.head.appendChild(style);
</script>
@endsection