<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'UGo') }} - @yield('title', 'Sistema de Puntos')</title>

    {{-- PWA Meta Tags --}}
    {!! app(\App\Services\PWAService::class)->generatePWAMetaTags() !!}
    
    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <style>
        .nav-blur {
            backdrop-filter: blur(12px);
            background-color: rgba(255, 255, 255, 0.9);
        }
    </style>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-gray-50 to-white"
      x-data="{ sidebarOpen: false, userMenuOpen: false }" 
      x-on:keydown.escape.window="sidebarOpen = false; userMenuOpen = false">
    <div class="min-h-screen relative">
        
        <nav class="nav-blur border-b border-gray-200/50 sticky top-0 z-50 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    
                    <div class="flex items-center space-x-8">
                        <div class="flex-shrink-0">
                            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                                <div class="w-12 h-12 bg-gradient-to-r from-itti-primary to-itti-secondary rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-110">
                                    <img src="{{ asset('logo.svg') }}" alt="UGo Logo" class="h-10 w-10 object-contain">
                                </div>
                                <div class="hidden sm:block">
                                    <h1 class="text-2xl font-bold bg-gradient-to-r from-itti-primary to-itti-secondary bg-clip-text text-transparent">
                                        UGo
                                    </h1>
                                    <p class="text-xs text-gray-500 font-medium">Sistema de Puntos</p>
                                </div>
                            </a>
                        </div>

                        <div class="hidden lg:flex lg:items-center lg:space-x-2">
                            <a href="{{ route('dashboard') }}" 
                               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                </svg>
                                Dashboard
                            </a>
                            <a href="{{ route('products.index') }}" 
                               class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5zM10 12a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                Productos
                            </a>
                            <a href="{{ route('favorites.index') }}" 
                               class="nav-link {{ request()->routeIs('favorites.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                </svg>
                                Favoritos
                            </a>
                            <a href="{{ route('orders.history') }}" 
                               class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM9 5a1 1 0 112 0v3.586l2.707 2.707a1 1 0 01-1.414 1.414L9.586 9.707A1 1 0 019 9V5z"/>
                                </svg>
                                Mis Pedidos
                            </a>
                            @if(auth()->user()?->is_admin ?? false)
                                <div class="h-6 w-px bg-gray-300 mx-2"></div>
                                <a href="{{ route('admin.orders') }}" 
                                   class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                                    </svg>
                                    Admin
                                </a>
                            @endif
                        </div>
                    </div>

                    
                    <div class="flex items-center space-x-4">
                        
                        @auth
                            <div class="hidden md:flex items-center space-x-3 bg-gradient-to-r from-itti-primary/10 to-itti-secondary/10 px-5 py-3 rounded-2xl border border-itti-primary/20 shadow-lg hover:shadow-xl transition-all duration-300 group">
                                <div class="w-8 h-8 bg-gradient-to-r from-itti-primary to-itti-secondary rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </div>
                                <div class="text-right">
                                    <span class="block text-lg font-bold text-gray-900">
                                        {{ number_format(auth()->user()->puntos_totales ?? 0) }}
                                    </span>
                                    <span class="text-xs text-itti-primary font-semibold uppercase tracking-wide">puntos</span>
                                </div>
                            </div>
                        @endauth

                        <div class="relative" x-data="{ open: false, notifications: [], unreadCount: 0, loading: false }">
                            <button @click="open = !open; loadNotifications()" 
                                    x-on:click.away="open = false"
                                    class="p-2 text-gray-500 hover:text-itti-primary hover:bg-itti-primary/5 rounded-xl transition-all duration-200 focus:ring-2 focus:ring-itti-primary/20 relative">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span x-show="unreadCount > 0" x-text="unreadCount > 9 ? '9+' : unreadCount" 
                                      class="absolute -top-1 -right-1 min-w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold" 
                                      style="display: none;"></span>
                            </button>

                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-strong border border-gray-200/50 z-50 overflow-hidden max-h-96"
                                 style="display: none;">
                                
                                <div class="bg-gradient-to-r from-itti-primary/5 to-itti-secondary/5 px-4 py-3 border-b border-gray-100">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-semibold text-gray-900">Notificaciones</h3>
                                        <button @click="loadNotifications()" :disabled="loading"
                                                class="p-1 rounded-lg hover:bg-itti-primary/10 transition-colors duration-200">
                                            <svg class="w-4 h-4 text-itti-primary" :class="{'animate-spin': loading}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="max-h-80 overflow-y-auto">
                                    <div x-show="loading" class="p-4 text-center text-gray-500">
                                        <svg class="animate-spin w-6 h-6 mx-auto mb-2" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Cargando notificaciones...
                                    </div>

                                    <div x-show="!loading && notifications.length === 0" class="p-6 text-center text-gray-500">
                                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-sm font-medium">No tienes notificaciones</p>
                                        <p class="text-xs text-gray-400 mt-1">Las actualizaciones de tus pedidos aparecerán aquí</p>
                                    </div>

                                    <template x-for="notification in notifications" :key="notification.id">
                                        <a :href="notification.url" @click="open = false"
                                           class="block px-4 py-3 hover:bg-gray-50 transition-colors duration-200 border-b border-gray-100 last:border-b-0">
                                            <div class="flex items-start space-x-3">
                                                <div class="flex-shrink-0">
                                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg"
                                                         :class="{
                                                             'bg-yellow-100': notification.status === 'pending',
                                                             'bg-blue-100': notification.status === 'processing', 
                                                             'bg-green-100': notification.status === 'completed',
                                                             'bg-red-100': notification.status === 'cancelled'
                                                         }">
                                                        <span x-text="notification.icon"></span>
                                                    </div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate" x-text="notification.title"></p>
                                                    <p class="text-sm text-gray-600" x-text="notification.message"></p>
                                                    <p class="text-xs text-gray-400 mt-1" x-text="notification.time"></p>
                                                </div>
                                            </div>
                                        </a>
                                    </template>
                                </div>

                                <div x-show="notifications.length > 0" class="bg-gray-50 px-4 py-3 border-t border-gray-100">
                                    <a href="{{ route('orders.history') }}" @click="open = false"
                                       class="block text-center text-sm text-itti-primary hover:text-itti-secondary font-medium">
                                        Ver todos los pedidos
                                    </a>
                                </div>
                            </div>
                        </div>

                        
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    x-on:click.away="open = false"
                                    class="flex items-center space-x-3 text-gray-700 hover:text-itti-primary focus:outline-none focus:ring-2 focus:ring-itti-primary/20 rounded-2xl p-2 hover:bg-itti-primary/5 transition-all duration-200 group">
                                <div class="w-11 h-11 bg-gradient-to-r from-itti-primary to-itti-secondary rounded-2xl flex items-center justify-center ring-2 ring-white shadow-lg group-hover:shadow-xl group-hover:scale-110 transition-all duration-300">
                                    <span id="user-initials" class="text-white text-sm font-bold">U</span>
                                </div>
                                <div class="hidden lg:block text-left">
                                    <div id="user-first-name" class="text-sm font-bold text-gray-900">Usuario</div>
                                    <div class="text-xs text-gray-500 font-medium">Empleado</div>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 transition-all duration-200" :class="{'rotate-180': open, 'text-itti-primary': open}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>

                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-3 w-72 bg-white rounded-2xl shadow-strong border border-gray-200/50 z-50 overflow-hidden"
                                 style="display: none;">
                                
                                <div class="bg-gradient-to-r from-itti-primary/5 to-itti-secondary/5 px-6 py-4 border-b border-gray-100">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-gradient-to-r from-itti-primary to-itti-secondary rounded-xl flex items-center justify-center">
                                            <span id="user-initials-dropdown" class="text-white text-sm font-bold">U</span>
                                        </div>
                                        <div class="flex-1">
                                            <div id="user-full-name" class="text-sm font-bold text-gray-900">Usuario</div>
                                            <div id="user-email" class="text-xs text-gray-500">Cargando...</div>
                                            <div class="text-xs text-itti-primary font-semibold mt-1">Empleado</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="lg:hidden px-6 py-3 border-b border-gray-100 bg-gradient-to-r from-itti-primary/5 to-itti-secondary/5">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-semibold text-gray-700">Puntos disponibles</span>
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-itti-primary" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            <span class="text-lg font-bold text-itti-primary">{{ number_format(auth()->user()->puntos_totales ?? 0) }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="py-2">
                                    <a href="{{ route('profile') }}" class="dropdown-item flex items-center group">
                                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-itti-primary/10 transition-colors duration-200">
                                            <svg class="w-4 h-4 text-gray-500 group-hover:text-itti-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold">Mi Perfil</div>
                                            <div class="text-xs text-gray-500">Configuración de cuenta</div>
                                        </div>
                                    </a>
                                    <a href="{{ route('orders.history') }}" class="dropdown-item flex items-center group">
                                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-itti-primary/10 transition-colors duration-200">
                                            <svg class="w-4 h-4 text-gray-500 group-hover:text-itti-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold">Mis Pedidos</div>
                                            <div class="text-xs text-gray-500">Historial de canjes</div>
                                        </div>
                                    </a>
                                    <a href="{{ route('favorites.index') }}" class="dropdown-item flex items-center group">
                                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-itti-primary/10 transition-colors duration-200">
                                            <svg class="w-4 h-4 text-gray-500 group-hover:text-itti-primary" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold">Favoritos</div>
                                            <div class="text-xs text-gray-500">Productos guardados</div>
                                        </div>
                                    </a>

                                    @if(auth()->user() && auth()->user()->rol_usuario === 'Administrador')
                                        <div class="border-t border-gray-100 mt-2 pt-2">
                                            <div class="px-4 py-2">
                                                <div class="text-xs font-bold text-purple-600 uppercase tracking-wider">Panel de Administración</div>
                                            </div>
                                            
                                            <a href="{{ route('admin.dashboard') }}" class="dropdown-item flex items-center group">
                                                <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center mr-3 group-hover:bg-purple-100 transition-colors duration-200">
                                                    <svg class="w-4 h-4 text-purple-500 group-hover:text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold">Dashboard</div>
                                                    <div class="text-xs text-gray-500">Panel principal</div>
                                                </div>
                                            </a>

                                            <a href="{{ route('admin.orders') }}" class="dropdown-item flex items-center group">
                                                <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center mr-3 group-hover:bg-purple-100 transition-colors duration-200">
                                                    <svg class="w-4 h-4 text-purple-500 group-hover:text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold">Gestionar Pedidos</div>
                                                    <div class="text-xs text-gray-500">Estados y procesamiento</div>
                                                </div>
                                            </a>

                                            <a href="{{ route('admin.employees') }}" class="dropdown-item flex items-center group">
                                                <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center mr-3 group-hover:bg-purple-100 transition-colors duration-200">
                                                    <svg class="w-4 h-4 text-purple-500 group-hover:text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold">Empleados</div>
                                                    <div class="text-xs text-gray-500">Gestión de usuarios</div>
                                                </div>
                                            </a>

                                            <a href="{{ route('admin.products') }}" class="dropdown-item flex items-center group">
                                                <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center mr-3 group-hover:bg-purple-100 transition-colors duration-200">
                                                    <svg class="w-4 h-4 text-purple-500 group-hover:text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold">Productos</div>
                                                    <div class="text-xs text-gray-500">Catálogo y stock</div>
                                                </div>
                                            </a>

                                            <a href="{{ route('admin.reports') }}" class="dropdown-item flex items-center group">
                                                <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center mr-3 group-hover:bg-purple-100 transition-colors duration-200">
                                                    <svg class="w-4 h-4 text-purple-500 group-hover:text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold">Reportes</div>
                                                    <div class="text-xs text-gray-500">Análisis y estadísticas</div>
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="border-t border-gray-100">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full dropdown-item flex items-center group text-red-600 hover:text-red-700 hover:bg-red-50/50">
                                            <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center mr-3 group-hover:bg-red-100 transition-colors duration-200">
                                                <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold">Cerrar Sesión</div>
                                                <div class="text-xs text-gray-400">Salir de la aplicación</div>
                                            </div>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="lg:hidden">
                            <button @click="sidebarOpen = !sidebarOpen" 
                                    type="button" 
                                    class="p-2 text-gray-500 hover:text-itti-primary hover:bg-itti-primary/5 focus:outline-none focus:ring-2 focus:ring-itti-primary/20 rounded-xl transition-all duration-200">
                                <span class="sr-only">Abrir menú principal</span>
                                <svg class="block h-6 w-6" :class="{'hidden': sidebarOpen}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                </svg>
                                <svg class="hidden h-6 w-6" :class="{'block': sidebarOpen}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </nav>

        <div class="lg:hidden">
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40"
                 style="display: none;"
                 @click="sidebarOpen = false"></div>

            <div x-show="sidebarOpen"
                 x-transition:enter="transition ease-in-out duration-300 transform"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-300 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="fixed inset-y-0 left-0 flex flex-col w-80 bg-white shadow-xl z-50"
                 style="display: none;">
                
                <div class="bg-gradient-to-r from-itti-primary to-itti-secondary p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <img src="{{ asset('logo.svg') }}" alt="UGo Logo" class="h-10 w-10 object-contain">
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white">UGo</h2>
                                <p class="text-xs text-white/80">Sistema de Puntos</p>
                            </div>
                        </div>
                        <button @click="sidebarOpen = false" class="p-2 rounded-lg bg-white/20 text-white hover:bg-white/30 transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="mt-4 p-3 bg-white/20 rounded-xl">
                        <div class="flex items-center justify-between">
                            <span class="text-white/90 font-medium">Puntos disponibles</span>
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-xl font-bold text-white">{{ number_format(auth()->user()->puntos_totales ?? 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                    <a href="{{ route('dashboard') }}" 
                       @click="sidebarOpen = false"
                       class="mobile-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('products.index') }}" 
                       @click="sidebarOpen = false"
                       class="mobile-nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5zM10 12a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        Productos
                    </a>
                    <a href="{{ route('favorites.index') }}" 
                       @click="sidebarOpen = false"
                       class="mobile-nav-item {{ request()->routeIs('favorites.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                        </svg>
                        Favoritos
                    </a>
                    <a href="{{ route('orders.history') }}" 
                       @click="sidebarOpen = false"
                       class="mobile-nav-item {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM9 5a1 1 0 112 0v3.586l2.707 2.707a1 1 0 01-1.414 1.414L9.586 9.707A1 1 0 019 9V5z"/>
                        </svg>
                        Mis Pedidos
                    </a>
                    @if(auth()->user()?->is_admin ?? false)
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Administración</p>
                            <a href="{{ route('admin.orders') }}" 
                               @click="sidebarOpen = false"
                               class="mobile-nav-item {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                                </svg>
                                Panel de Administración
                            </a>
                        </div>
                    @endif
                </nav>
            </div>
        </div>

        
        <main class="relative">
            <div class="absolute inset-0 bg-gradient-to-br from-gray-50 via-white to-gray-50 pointer-events-none"></div>
            
            <div class="relative z-10 max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                
                @if(session('success'))
                    <div class="mb-8 bg-gradient-to-r from-green-50 via-emerald-50 to-green-50 border border-green-200/50 text-green-800 px-6 py-4 rounded-2xl shadow-soft slide-up group hover:shadow-lg transition-all duration-300" 
                         data-flash-message 
                         x-data="{ show: true }" 
                         x-show="show"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-bold">¡Excelente!</p>
                                    <p class="text-sm">{{ session('success') }}</p>
                                </div>
                            </div>
                            <button @click="show = false" class="ml-4 p-1 rounded-lg hover:bg-green-100 transition-colors duration-200">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-8 bg-gradient-to-r from-red-50 via-rose-50 to-red-50 border border-red-200/50 text-red-800 px-6 py-4 rounded-2xl shadow-soft slide-up group hover:shadow-lg transition-all duration-300" 
                         data-flash-message 
                         x-data="{ show: true }" 
                         x-show="show"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-rose-500 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L10 11.414l2.707-2.707a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-bold">Oops! Algo salió mal</p>
                                    <p class="text-sm">{{ session('error') }}</p>
                                </div>
                            </div>
                            <button @click="show = false" class="ml-4 p-1 rounded-lg hover:bg-red-100 transition-colors duration-200">
                                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="mb-8 bg-gradient-to-r from-yellow-50 via-amber-50 to-yellow-50 border border-yellow-200/50 text-yellow-800 px-6 py-4 rounded-2xl shadow-soft slide-up group hover:shadow-lg transition-all duration-300" 
                         data-flash-message 
                         x-data="{ show: true }" 
                         x-show="show"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-gradient-to-r from-yellow-500 to-amber-500 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-bold">¡Atención!</p>
                                    <p class="text-sm">{{ session('warning') }}</p>
                                </div>
                            </div>
                            <button @click="show = false" class="ml-4 p-1 rounded-lg hover:bg-yellow-100 transition-colors duration-200">
                                <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                @if(session('info'))
                    <div class="mb-8 bg-gradient-to-r from-blue-50 via-indigo-50 to-blue-50 border border-blue-200/50 text-blue-800 px-6 py-4 rounded-2xl shadow-soft slide-up group hover:shadow-lg transition-all duration-300" 
                         data-flash-message 
                         x-data="{ show: true }" 
                         x-show="show"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-bold">Información</p>
                                    <p class="text-sm">{{ session('info') }}</p>
                                </div>
                            </div>
                            <button @click="show = false" class="ml-4 p-1 rounded-lg hover:bg-blue-100 transition-colors duration-200">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @livewireScripts
    
    <script>
        // Global App State
        window.app = {
            isLoading: false,
            theme: 'light',
            user: @json(auth()->user())
        };

        // Global notification loading function for Alpine.js
        window.loadNotifications = async function() {
            const notificationComponent = document.querySelector('[x-data*="notifications"]');
            if (!notificationComponent) return;
            
            const token = localStorage.getItem('firebase_token');
            if (!token) {
                console.log('No Firebase token available for notifications');
                return;
            }

            // Set loading state
            Alpine.evaluate(notificationComponent, 'loading = true');

            try {
                const response = await fetch('/api/notificaciones', {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    console.log('Notifications loaded:', data);
                    
                    // Update Alpine data
                    Alpine.evaluate(notificationComponent, `
                        notifications = ${JSON.stringify(data.notifications)};
                        unreadCount = ${data.unread_count || 0};
                    `);
                } else {
                    console.error('Failed to load notifications:', response.status);
                    Alpine.evaluate(notificationComponent, 'notifications = []');
                }
            } catch (error) {
                console.error('Error loading notifications:', error);
                Alpine.evaluate(notificationComponent, 'notifications = []');
            } finally {
                // Clear loading state
                Alpine.evaluate(notificationComponent, 'loading = false');
            }
        };

        // Update user interface with Firebase data
        window.updateUserInterface = function(firebaseUser) {
            if (!firebaseUser || !firebaseUser.displayName && !firebaseUser.email) {
                return;
            }

            let displayName = firebaseUser.displayName;
            
            // If no displayName, extract from email
            if (!displayName && firebaseUser.email) {
                const emailLocal = firebaseUser.email.split('@')[0];
                const parts = emailLocal.split(/[._-]/);
                displayName = parts.map(part => 
                    part.charAt(0).toUpperCase() + part.slice(1).toLowerCase()
                ).join(' ');
            }

            if (displayName) {
                const names = displayName.trim().split(' ');
                const firstName = names[0];
                
                // For Spanish naming convention: [First] [Middle(s)] [Paternal] [Maternal]
                // We want First + Paternal (usually 3rd position for 4+ names, or 2nd for 2-3 names)
                let lastName = '';
                if (names.length >= 4) {
                    // 4+ names: assume [First] [Middle(s)] [Paternal] [Maternal]
                    lastName = names[names.length - 2]; // Paternal surname (second to last)
                } else if (names.length >= 2) {
                    // 2-3 names: use second name as surname
                    lastName = names[1];
                }
                
                const fullName = firstName + (lastName ? ' ' + lastName : '');
                
                // Get first letter of first name for initials
                const initials = firstName.charAt(0).toUpperCase();
                
                // Update navigation elements
                const userInitials = document.getElementById('user-initials');
                const userFirstName = document.getElementById('user-first-name');
                const userInitialsDropdown = document.getElementById('user-initials-dropdown');
                const userFullName = document.getElementById('user-full-name');
                const userEmail = document.getElementById('user-email');
                
                if (userInitials) userInitials.textContent = initials;
                if (userFirstName) userFirstName.textContent = firstName;
                if (userInitialsDropdown) userInitialsDropdown.textContent = initials;
                if (userFullName) userFullName.textContent = fullName;
                if (userEmail) userEmail.textContent = firebaseUser.email || '';
                
                console.log('User interface updated:', { firstName, fullName, initials, email: firebaseUser.email });
            }
        };

        // Listen for Firebase auth completion
        window.addEventListener('auth-completed', (event) => {
            console.log('Auth completed event received:', event.detail);
            if (event.detail && event.detail.user) {
                updateUserInterface(event.detail.user);
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips and enhanced UX features
            initializeEnhancedFeatures();
            
            // Check if auth is already completed
            if (window.authInitialized && window.auth?.currentUser) {
                updateUserInterface(window.auth.currentUser);
            }
            
            // Auto-hide flash messages after 8 seconds
            const flashMessages = document.querySelectorAll('[data-flash-message]');
            flashMessages.forEach(message => {
                setTimeout(() => {
                    const closeButton = message.querySelector('button');
                    if (closeButton) {
                        closeButton.click();
                    }
                }, 8000);
            });
        });

        // Enhanced Features Initialization
        function initializeEnhancedFeatures() {
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Enhanced loading states for buttons
            document.querySelectorAll('button[type="submit"], a.btn-primary').forEach(button => {
                button.addEventListener('click', function() {
                    if (!this.disabled) {
                        this.classList.add('opacity-75');
                        const loadingText = this.getAttribute('data-loading-text') || 'Cargando...';
                        const originalText = this.innerHTML;
                        this.innerHTML = `
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            ${loadingText}
                        `;
                        
                        // Reset after 3 seconds if no page navigation occurs
                        setTimeout(() => {
                            this.innerHTML = originalText;
                            this.classList.remove('opacity-75');
                        }, 3000);
                    }
                });
            });

            // Enhanced form validation feedback
            document.querySelectorAll('.form-input').forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.required && !this.value) {
                        this.classList.add('border-red-300', 'bg-red-50');
                        this.classList.remove('border-gray-300');
                    } else {
                        this.classList.remove('border-red-300', 'bg-red-50');
                        this.classList.add('border-gray-300');
                    }
                });
            });
        }

        // Global Toast Function
        window.showToast = function(message, type = 'success') {
            const toast = document.createElement('div');
            const icons = {
                success: `<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>`,
                error: `<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L10 11.414l2.707-2.707a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>`,
                warning: `<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>`
            };
            
            const colors = {
                success: 'from-green-500 to-emerald-500',
                error: 'from-red-500 to-rose-500',
                warning: 'from-yellow-500 to-amber-500'
            };

            toast.className = 'fixed top-24 right-4 z-50 max-w-sm w-full bg-white rounded-2xl shadow-strong border border-gray-200 p-4 slide-up';
            toast.innerHTML = `
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-r ${colors[type]} rounded-xl flex items-center justify-center shadow-lg">
                            ${icons[type]}
                        </div>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-gray-900">${message}</p>
                    </div>
                    <div class="ml-4">
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" 
                                class="p-1 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            // Auto-remove after 4 seconds
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 4000);
        };

        // Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('UGo Service Worker registered successfully:', registration);
                    })
                    .catch(function(error) {
                        console.log('Service Worker registration failed:', error);
                    });
            });
        }

        // Performance monitoring
        window.addEventListener('load', function() {
            if ('performance' in window) {
                setTimeout(() => {
                    const loadTime = performance.timing.loadEventEnd - performance.timing.navigationStart;
                    console.log(`UGo App loaded in ${loadTime}ms`);
                }, 0);
            }
        });
    </script>
    
    {{-- Additional Scripts --}}
    @stack('scripts')
</body>
</html>