<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'UGo') }} - @yield('title', 'Sistema de Puntos')</title>

    
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        
        <nav class="bg-white shadow-soft border-b border-gray-100 sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        
                        <div class="flex-shrink-0">
                            <a href="{{ route('dashboard') }}" class="flex items-center hover:opacity-90 transition-opacity">
                                <img src="{{ asset('logo.svg') }}" alt="UGo Logo" class="h-10 w-auto">
                            </a>
                        </div>

                        
                        <div class="hidden md:ml-8 md:flex md:space-x-8">
                            <a href="{{ route('dashboard') }}" 
                               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('products.index') }}" 
                               class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                                Productos
                            </a>
                            <a href="{{ route('favorites.index') }}" 
                               class="nav-link {{ request()->routeIs('favorites.*') ? 'active' : '' }}">
                                Favoritos
                            </a>
                            @if(auth()->user()?->is_admin ?? false)
                                <a href="{{ route('admin.orders') }}" 
                                   class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                                    Administración
                                </a>
                            @endif
                        </div>
                    </div>

                    
                    <div class="flex items-center space-x-4">
                        
                        @auth
                            <div class="hidden md:flex items-center space-x-2 bg-gradient-to-r from-blue-50 to-indigo-50 px-4 py-2 rounded-full border border-blue-100 shadow-sm">
                                <div class="w-6 h-6 bg-gradient-blue rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-semibold text-blue-900">
                                    {{ number_format(auth()->user()->puntos_totales ?? 0) }}
                                </span>
                                <span class="text-xs text-blue-600 font-medium">puntos</span>
                            </div>
                        @endauth

                        
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-lg p-2 transition-all hover:bg-gray-50 interactive-scale">
                                <div class="w-9 h-9 bg-gradient-blue rounded-full flex items-center justify-center ring-2 ring-white shadow-sm">
                                    <span class="text-white text-sm font-semibold">
                                        {{ auth()->user() ? strtoupper(substr(auth()->user()->nombre, 0, 1)) : 'U' }}
                                    </span>
                                </div>
                                <div class="hidden md:block text-left">
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ auth()->user()?->nombre ?? 'Usuario' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ auth()->user()?->rol_usuario ?? 'Empleado' }}
                                    </div>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 transition-transform" :class="{'rotate-180': open}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>

                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-strong py-2 z-50 ring-1 ring-black ring-opacity-5 border border-gray-100">
                                
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <div class="text-sm font-semibold text-gray-900">{{ auth()->user()?->nombre ?? 'Usuario' }}</div>
                                    <div class="text-xs text-gray-500 truncate">{{ auth()->user()?->email ?? '' }}</div>
                                </div>
                                
                                
                                <div class="py-1">
                                    <a href="{{ route('profile') }}" class="dropdown-item flex items-center">
                                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Mi Perfil
                                    </a>
                                    <a href="{{ route('orders.history') }}" class="dropdown-item flex items-center">
                                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        Historial de Pedidos
                                    </a>
                                    
                                    
                                    <div class="md:hidden px-4 py-2 border-t border-gray-100 mt-1">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-medium text-gray-700">Puntos disponibles:</span>
                                            <span class="text-sm font-bold text-teal-600">{{ number_format(auth()->user()->puntos_totales ?? 0) }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="border-t border-gray-100 py-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item w-full text-left flex items-center text-red-600 hover:text-red-700 hover:bg-red-50">
                                            <svg class="w-4 h-4 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                            </svg>
                                            Cerrar Sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        
                        <div class="md:hidden">
                            <button type="button" 
                                    class="mobile-menu-button text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-teal-500 p-2 rounded-md"
                                    aria-controls="mobile-menu" 
                                    aria-expanded="false">
                                <span class="sr-only">Abrir menú principal</span>
                                <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="mobile-menu hidden md:hidden" id="mobile-menu">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-gray-50">
                    <a href="{{ route('dashboard') }}" class="mobile-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('products.index') }}" class="mobile-nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        Productos
                    </a>
                    <a href="{{ route('favorites.index') }}" class="mobile-nav-link {{ request()->routeIs('favorites.*') ? 'active' : '' }}">
                        Favoritos
                    </a>
                    @if(auth()->user()?->is_admin ?? false)
                        <a href="{{ route('admin.orders') }}" class="mobile-nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                            Administración
                        </a>
                    @endif
                </div>
            </div>
        </nav>

        
        <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl shadow-soft slide-up" data-flash-message>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl shadow-soft slide-up" data-flash-message>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L10 11.414l2.707-2.707a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-6 bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-xl shadow-soft slide-up" data-flash-message>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">{{ session('warning') }}</span>
                    </div>
                </div>
            @endif

            @if(session('info'))
                <div class="mb-6 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-xl shadow-soft slide-up" data-flash-message>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">{{ session('info') }}</span>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @livewireScripts
    
    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.querySelector('.mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        });
    </script>
</body>
</html>