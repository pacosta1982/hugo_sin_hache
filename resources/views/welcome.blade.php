<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'UGo') }} - Sistema de Puntos y Recompensas</title>
    
    {{-- PWA Meta Tags --}}
    {!! app(\App\Services\PWAService::class)->generatePWAMetaTags() !!}
    
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .hero-bg-pattern {
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(10, 221, 144, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(0, 174, 110, 0.1) 0%, transparent 50%);
        }
    </style>
</head>
<body class="font-sans antialiased">
    <section class="hero-section hero-bg-pattern min-h-screen flex items-center">
        <nav class="absolute top-0 left-0 right-0 z-20 py-6">
            <div class="container flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('logo.svg') }}" alt="UGo Logo" class="h-10 w-auto">
                    <h1 class="text-2xl font-bold text-white">UGo</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="btn-ghost text-white border-white/20 hover:bg-white/10">
                        Iniciar Sesión
                    </a>
                    <a href="{{ route('login') }}" class="btn-primary">
                        Comenzar
                    </a>
                </div>
            </div>
        </nav>

        <div class="hero-content w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="hero-title">
                        Transforma tus 
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-itti-primary to-itti-secondary">
                            logros
                        </span> 
                        en recompensas
                    </h1>
                    <p class="hero-subtitle">
                        Sistema de puntos inteligente que reconoce tu esfuerzo y te permite canjear increíbles recompensas. 
                        Acumula puntos, alcanza metas y disfruta de beneficios exclusivos.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 mb-12">
                        <a href="{{ route('login') }}" class="btn-primary btn-lg">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Comenzar Ahora
                        </a>
                        <button class="btn-secondary btn-lg" onclick="document.getElementById('features').scrollIntoView({behavior: 'smooth'})">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Descubrir Más
                        </button>
                    </div>

                    <div class="grid grid-cols-3 gap-8">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-itti-primary mb-2">1000+</div>
                            <div class="text-gray-300 text-sm">Usuarios Activos</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-itti-primary mb-2">50+</div>
                            <div class="text-gray-300 text-sm">Productos</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-itti-primary mb-2">99%</div>
                            <div class="text-gray-300 text-sm">Satisfacción</div>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="relative z-10">
                        <div class="card-gradient p-6 transform rotate-3 hover:rotate-1 transition-transform duration-300">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-semibold text-gray-900">Dashboard</h3>
                                <div class="w-3 h-3 bg-itti-primary rounded-full animate-pulse"></div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div class="bg-white p-4 rounded-lg shadow-sm">
                                    <div class="text-2xl font-bold text-itti-primary">2,540</div>
                                    <div class="text-sm text-gray-600">Puntos</div>
                                </div>
                                <div class="bg-white p-4 rounded-lg shadow-sm">
                                    <div class="text-2xl font-bold text-green-600">12</div>
                                    <div class="text-sm text-gray-600">Canjes</div>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-itti-primary rounded-full"></div>
                                    <div class="flex-1">
                                        <div class="skeleton-text w-3/4"></div>
                                        <div class="skeleton-text w-1/2"></div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                    <div class="flex-1">
                                        <div class="skeleton-text w-2/3"></div>
                                        <div class="skeleton-text w-1/3"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="absolute -top-4 -right-4 w-72 h-72 bg-itti-primary/10 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-4 -left-4 w-72 h-72 bg-itti-secondary/10 rounded-full blur-3xl"></div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
        </div>
    </section>

    <section id="features" class="py-20 bg-gradient-to-b from-white to-gray-50">
        <div class="container">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">
                    ¿Por qué elegir 
                    <span class="text-itti-primary">UGo</span>?
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Descubre las características que hacen de UGo la mejor plataforma de recompensas
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="card-gradient p-8 text-center group hover:scale-105 transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-r from-itti-primary to-itti-secondary rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Acumula Puntos</h3>
                    <p class="text-gray-600">
                        Gana puntos por cada logro, tarea completada o meta alcanzada. El sistema reconoce automáticamente tu progreso.
                    </p>
                </div>

                <div class="card-gradient p-8 text-center group hover:scale-105 transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-r from-itti-primary to-itti-secondary rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Catálogo Variado</h3>
                    <p class="text-gray-600">
                        Explora cientos de productos y experiencias disponibles para canjear con tus puntos acumulados.
                    </p>
                </div>

                <div class="card-gradient p-8 text-center group hover:scale-105 transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-r from-itti-primary to-itti-secondary rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Proceso Rápido</h3>
                    <p class="text-gray-600">
                        Canje instantáneo y seguimiento en tiempo real de tus pedidos. Sin complicaciones, sin esperas.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-gradient-to-r from-itti-dark-purple to-itti-dark">
        <div class="container">
            <div class="text-center">
                <h2 class="text-4xl font-bold text-white mb-6">
                    ¿Listo para comenzar?
                </h2>
                <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto">
                    Únete a miles de usuarios que ya están transformando sus logros en recompensas increíbles.
                </p>
                <a href="{{ route('login') }}" class="btn-primary btn-lg">
                    Iniciar Sesión
                    <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </path>
                </svg>
                </a>
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 text-white py-12">
        <div class="container">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <img src="{{ asset('logo.svg') }}" alt="UGo Logo" class="h-8 w-auto">
                        <h3 class="text-xl font-bold">UGo</h3>
                    </div>
                    <p class="text-gray-400">
                        Sistema de puntos y recompensas que transforma tus logros en beneficios tangibles.
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Plataforma</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-itti-primary transition-colors">Dashboard</a></li>
                        <li><a href="#" class="hover:text-itti-primary transition-colors">Productos</a></li>
                        <li><a href="#" class="hover:text-itti-primary transition-colors">Mi Perfil</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Soporte</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-itti-primary transition-colors">Centro de Ayuda</a></li>
                        <li><a href="#" class="hover:text-itti-primary transition-colors">Contacto</a></li>
                        <li><a href="#" class="hover:text-itti-primary transition-colors">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Legal</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-itti-primary transition-colors">Términos de Uso</a></li>
                        <li><a href="#" class="hover:text-itti-primary transition-colors">Privacidad</a></li>
                        <li><a href="#" class="hover:text-itti-primary transition-colors">Cookies</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} UGo. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>