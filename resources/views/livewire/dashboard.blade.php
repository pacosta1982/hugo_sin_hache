<div class="space-y-8">
    
    <div id="dashboard-loading" class="text-center py-20 fade-in">
        <div class="inline-block">
            <div class="spinner-ring-lg mx-auto mb-6"></div>
            <div class="space-y-2">
                <p class="text-lg font-semibold text-gray-900">Cargando tu dashboard...</p>
                <p class="text-gray-500">Un momento por favor</p>
            </div>
        </div>
    </div>

    
    <div id="dashboard-content" class="hidden space-y-8 fade-in-up">
        
        
        <div class="relative card-entrance">
            <div class="bg-gradient-to-r from-itti-primary/10 via-itti-secondary/10 to-itti-primary/5 rounded-3xl p-4 sm:p-6 lg:p-8 border border-itti-primary/20">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-16 h-16 bg-gradient-to-r from-itti-primary to-itti-secondary rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10m0 0V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2m10 0v10a2 2 0 01-2 2H9a2 2 0 01-2-2V8m10 0H7m0 0v10a2 2 0 002 2h6a2 2 0 002-2V8"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-responsive-lg font-bold text-gray-900">
                                    ¡Hola, <span id="employee-name" class="text-itti-primary">{{ $employee ? $employee->nombre : '' }}</span>!
                                </h1>
                                <p class="text-gray-600 mt-1 text-responsive-base">
                                    Bienvenido a tu dashboard de puntos UGo
                                </p>
                            </div>
                        </div>
                        
                        
                        <div class="flex flex-wrap gap-3 mt-6">
                            <a href="{{ route('products.index') }}" class="btn-primary btn-sm">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5z" clip-rule="evenodd"/>
                                </svg>
                                Explorar Productos
                            </a>
                            <a href="{{ route('favorites.index') }}" class="btn-secondary btn-sm">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                </svg>
                                Mis Favoritos
                            </a>
                            <a href="{{ route('orders.history') }}" class="btn-secondary btn-sm">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM9 5a1 1 0 112 0v3.586l2.707 2.707a1 1 0 01-1.414 1.414L9.586 9.707A1 1 0 019 9V5z"/>
                                </svg>
                                Historial
                            </a>
                        </div>
                    </div>
                    
                    
                    <div class="hidden lg:block">
                        <div class="w-32 h-32 bg-gradient-to-br from-itti-primary/20 to-itti-secondary/20 rounded-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-itti-primary" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        
        <div class="mobile-card-stack grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 lg:gap-6">
            
            
            <div class="card-stats group stagger-item hover-glow">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Puntos Disponibles</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_points'] ?? 0) }}</p>
                            <div class="flex items-center mt-3">
                                <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full font-medium">
                                    <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Disponible para canje
                                </span>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 bg-gradient-to-r from-itti-primary to-itti-secondary rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-all duration-300">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card-stats group stagger-item hover-glow">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Puntos Canjeados</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['redeemed_points'] ?? 0) }}</p>
                            <div class="flex items-center mt-3">
                                <span class="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded-full font-medium">
                                    Total histórico
                                </span>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-all duration-300">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card-stats group stagger-item hover-glow">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Total Pedidos</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_orders'] ?? 0 }}</p>
                            <div class="flex items-center mt-3">
                                <span class="text-xs text-purple-600 bg-purple-100 px-2 py-1 rounded-full font-medium">
                                    Canjes realizados
                                </span>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-all duration-300">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM13 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1zM17 8a1 1 0 011 1v6a1 1 0 11-2 0V9a1 1 0 011-1z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card-stats group stagger-item hover-glow">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Pedidos Pendientes</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['pending_orders'] ?? 0 }}</p>
                            <div class="flex items-center mt-3">
                                @if(($stats['pending_orders'] ?? 0) > 0)
                                    <span class="text-xs text-amber-600 bg-amber-100 px-2 py-1 rounded-full font-medium">
                                        <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        En proceso
                                    </span>
                                @else
                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full font-medium">
                                        Todo al día
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 bg-gradient-to-r from-amber-500 to-yellow-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-all duration-300">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            
            <div class="lg:col-span-2">
                <div class="card interactive-card fade-in-left">
                    <div class="card-header">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">Actividad Reciente</h3>
                                    <p class="text-sm text-gray-500">Tus últimos movimientos</p>
                                </div>
                            </div>
                            <a href="{{ route('orders.history') }}" class="btn-ghost btn-sm">
                                Ver todo
                                <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($recentOrders->count() > 0)
                            <div class="flow-root">
                                <ul role="list" class="space-y-6">
                                    @foreach($recentOrders as $index => $order)
                                        <li class="group stagger-item">
                                            <div class="flex items-start space-x-4 p-4 rounded-xl hover:bg-gray-50 transition-colors duration-200 hover-float">
                                                <div class="flex-shrink-0">
                                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-lg
                                                        {{ $order->estado === 'pending' ? 'bg-gradient-to-r from-yellow-500 to-amber-500' : '' }}
                                                        {{ $order->estado === 'processing' ? 'bg-gradient-to-r from-blue-500 to-indigo-500' : '' }}
                                                        {{ $order->estado === 'completed' ? 'bg-gradient-to-r from-green-500 to-emerald-500' : '' }}
                                                        {{ $order->estado === 'cancelled' ? 'bg-gradient-to-r from-red-500 to-rose-500' : '' }}
                                                        group-hover:scale-110 transition-transform duration-200">
                                                        @if($order->estado === 'completed')
                                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                            </svg>
                                                        @elseif($order->estado === 'pending')
                                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                            </svg>
                                                        @elseif($order->estado === 'processing')
                                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                                            </svg>
                                                        @else
                                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <p class="text-sm font-semibold text-gray-900">
                                                            {{ $order->producto_nombre }}
                                                        </p>
                                                        <span class="status-badge 
                                                            {{ $order->estado === 'pending' ? 'status-pending' : '' }}
                                                            {{ $order->estado === 'processing' ? 'status-processing' : '' }}
                                                            {{ $order->estado === 'completed' ? 'status-completed' : '' }}
                                                            {{ $order->estado === 'cancelled' ? 'status-cancelled' : '' }}">
                                                            {{ $order->estado_display }}
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center space-x-4">
                                                            <span class="text-sm text-itti-primary font-semibold">
                                                                {{ $order->puntos_utilizados }} puntos
                                                            </span>
                                                            <span class="text-sm text-gray-500">
                                                                {{ $order->fecha->diffForHumans() }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <div class="text-center py-16">
                                <div class="w-24 h-24 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Sin actividad reciente</h3>
                                <p class="text-gray-500 mb-8 max-w-sm mx-auto">¡Comienza canjeando tu primer producto y ve tu actividad aquí!</p>
                                <a href="{{ route('products.index') }}" class="btn-primary">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5z" clip-rule="evenodd"/>
                                    </svg>
                                    Explorar Productos
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            
            
            <div class="space-y-6">
                
                <div class="card interactive-card fade-in-right">
                    <div class="card-header">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-r from-itti-primary to-itti-secondary rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5zM10 12a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Productos Destacados</h3>
                                    <p class="text-sm text-gray-500">Los más populares</p>
                                </div>
                            </div>
                            <a href="{{ route('products.index') }}" class="btn-ghost btn-sm">
                                Ver todos
                                <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($featuredProducts->count() > 0)
                            <div class="space-y-4">
                                @foreach($featuredProducts->take(4) as $product)
                                    @php
                                        $userPoints = $stats['total_points'] ?? 0;
                                        $canAfford = $userPoints >= $product->costo_puntos;
                                        $hasStock = $product->stock == -1 || $product->stock > 0;
                                        $isAvailable = $canAfford && $hasStock && $product->activo;
                                    @endphp
                                    <div class="group p-4 rounded-xl hover:bg-gradient-to-r hover:from-itti-primary/5 hover:to-itti-secondary/5 border border-transparent hover:border-itti-primary/20 cursor-pointer transition-all duration-200 {{ !$isAvailable ? 'opacity-60' : '' }}"
                                         wire:click="redirectToProduct({{ $product->id }})">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0 relative">
                                                <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200 group-hover:from-itti-primary/10 group-hover:to-itti-secondary/10">
                                                    @if(!$isAvailable)
                                                        <div class="absolute -top-1 -right-1 w-6 h-6 bg-gray-400 rounded-full flex items-center justify-center">
                                                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                                            </svg>
                                                        </div>
                                                    @else
                                                        <div class="absolute -top-1 -right-1 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                    <svg class="w-8 h-8 text-gray-400 group-hover:text-itti-primary transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5zM10 12a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-semibold text-gray-900 group-hover:text-itti-primary transition-colors duration-200 truncate">
                                                    {{ $product->nombre }}
                                                </h4>
                                                <p class="text-xs text-gray-500 truncate mt-1">
                                                    {{ $product->categoria }}
                                                </p>
                                                <div class="flex items-center space-x-2 mt-1">
                                                    @if($product->stock !== -1)
                                                        <span class="text-xs {{ $hasStock ? 'text-green-600' : 'text-red-600' }}">
                                                            Stock: {{ $product->stock }} unidades
                                                        </span>
                                                    @else
                                                        <span class="text-xs text-green-600">
                                                            Stock ilimitado
                                                        </span>
                                                    @endif
                                                    @if(!$canAfford)
                                                        <span class="text-xs text-red-600 bg-red-50 px-2 py-0.5 rounded">
                                                            Puntos insuficientes
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0 text-right">
                                                <p class="text-lg font-bold {{ $canAfford ? 'text-itti-primary' : 'text-gray-400' }}">
                                                    {{ number_format($product->costo_puntos) }}
                                                </p>
                                                <p class="text-xs text-gray-500">puntos</p>
                                                @if($isAvailable)
                                                    <p class="text-xs text-green-600 font-medium">Disponible</p>
                                                @else
                                                    <p class="text-xs text-gray-500">No disponible</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if($featuredProducts->count() > 4)
                                <div class="mt-6 pt-4 border-t border-gray-200">
                                    <a href="{{ route('products.index') }}" class="w-full btn-secondary text-center">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5z" clip-rule="evenodd"/>
                                        </svg>
                                        Ver {{ $featuredProducts->count() - 4 }} productos más
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-12">
                                <div class="w-20 h-20 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-10 h-10 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <h4 class="text-sm font-semibold text-gray-900 mb-2">No hay productos disponibles</h4>
                                <p class="text-sm text-gray-500">Los productos aparecerán aquí cuando estén disponibles</p>
                            </div>
                        @endif
                    </div>
                </div>

                
                <div class="card interactive-card scale-in">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gradient-to-r from-itti-primary to-itti-secondary rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-2">¡Sigue así!</h4>
                            <p class="text-sm text-gray-600 mb-4">Tu rendimiento es excelente. Continúa acumulando puntos.</p>
                            <div class="text-center">
                                <span class="text-2xl font-bold text-itti-primary">{{ number_format($stats['total_points'] ?? 0) }}</span>
                                <p class="text-xs text-gray-500">puntos disponibles</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize dashboard with better error handling
            initializeDashboard();
        });

        async function initializeDashboard() {
            let attempts = 0;
            const maxAttempts = 3;
            
            while (attempts < maxAttempts) {
                try {
                    console.log(`🎯 Starting dashboard load (attempt ${attempts + 1}/${maxAttempts})...`);
                    await loadDashboard();
                    return; // Success, exit retry loop
                } catch (error) {
                    attempts++;
                    console.error(`❌ Dashboard loading failed (attempt ${attempts}/${maxAttempts}):`, error);
                    
                    if (attempts < maxAttempts) {
                        console.log(`🔄 Retrying in ${attempts * 2} seconds...`);
                        await new Promise(resolve => setTimeout(resolve, attempts * 2000));
                    } else {
                        console.error('❌ All dashboard loading attempts failed');
                        showDashboardError('Error al cargar el dashboard. Intenta refrescar la página.');
                        // Don't redirect immediately, give user option to retry
                        showRetryButton();
                    }
                }
            }
        }

        async function loadDashboard() {
            try {
                // Step 1: Wait for Firebase auth to initialize with timeout
                updateLoadingProgress(1, 4, 'Inicializando autenticación...');
                console.log('⏳ Waiting for auth initialization...');
                const user = await Promise.race([
                    waitForAuth(),
                    new Promise((_, reject) => setTimeout(() => reject(new Error('Auth timeout')), 10000))
                ]);
                
                if (!user) {
                    throw new Error('No authenticated user found');
                }

                console.log('✅ Auth user found:', user.email);

                // Step 2: Get current user data from API with timeout
                updateLoadingProgress(2, 4, 'Obteniendo datos de usuario...');
                console.log('📡 Calling /api/me...');
                const response = await Promise.race([
                    apiRequest('/api/me'),
                    new Promise((_, reject) => setTimeout(() => reject(new Error('API timeout')), 5000))
                ]);
                
                console.log('📨 API response:', response);
                
                if (!response || !response.employee) {
                    throw new Error('Invalid API response: missing employee data');
                }

                console.log('👤 Employee data received:', response.employee);
                
                // Update employee name
                const nameEl = document.getElementById('employee-name');
                if (nameEl) {
                    nameEl.textContent = response.employee.nombre;
                }
                
                // Step 3: Call Livewire method to load dashboard data
                updateLoadingProgress(3, 4, 'Cargando datos del dashboard...');
                if (typeof Livewire === 'undefined') {
                    throw new Error('Livewire not available');
                }

                console.log('🔧 Calling Livewire loadDashboardData...');
                const livewireComponent = Livewire.find('{{ $_instance->getId() }}');
                
                if (!livewireComponent) {
                    throw new Error('Livewire component not found');
                }

                await livewireComponent.call('loadDashboardData', response.employee);
                console.log('✅ Livewire call completed');
                
                // Step 4: Wait for Livewire to re-render and show content
                updateLoadingProgress(4, 4, 'Finalizando...');
                await new Promise(resolve => setTimeout(resolve, 1000));
                
                // Hide loading, show content
                showDashboardContent();
                console.log('✅ Dashboard content displayed successfully');
                
            } catch (error) {
                console.error('❌ Dashboard loading error:', error);
                throw error; // Re-throw for retry logic
            }
        }

        function showDashboardContent() {
            const loadingEl = document.getElementById('dashboard-loading');
            const contentEl = document.getElementById('dashboard-content');
            
            if (loadingEl) {
                loadingEl.classList.add('hidden');
                console.log('✅ Loading hidden');
            }
            
            if (contentEl) {
                contentEl.classList.remove('hidden');
                console.log('✅ Content shown');
            }
        }

        function showDashboardError(message) {
            const loadingEl = document.getElementById('dashboard-loading');
            if (loadingEl) {
                loadingEl.innerHTML = `
                    <div class="text-center py-20">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <p class="text-lg font-semibold text-gray-900 mb-2">Error al cargar dashboard</p>
                        <p class="text-gray-500 mb-4">${message}</p>
                    </div>
                `;
            }
        }

        function showRetryButton() {
            const loadingEl = document.getElementById('dashboard-loading');
            if (loadingEl) {
                const retryButton = document.createElement('button');
                retryButton.className = 'btn-primary';
                retryButton.textContent = 'Reintentar';
                retryButton.onclick = () => {
                    loadingEl.innerHTML = `
                        <div class="inline-block">
                            <div class="spinner-ring-lg mx-auto mb-6"></div>
                            <div class="space-y-2">
                                <p class="text-lg font-semibold text-gray-900">Cargando tu dashboard...</p>
                                <p class="text-gray-500">Un momento por favor</p>
                            </div>
                        </div>
                    `;
                    initializeDashboard();
                };
                loadingEl.querySelector('.text-center').appendChild(retryButton);
            }
        }

        // Add window error handler for better debugging
        window.addEventListener('error', function(event) {
            console.error('🚨 Global error:', event.error);
        });

        window.addEventListener('unhandledrejection', function(event) {
            console.error('🚨 Unhandled promise rejection:', event.reason);
        });

        // Listen for Livewire events
        document.addEventListener('livewire:init', function() {
            Livewire.on('dashboard-loaded', (event) => {
                console.log('✅ Dashboard loaded event received:', event);
                showToast('Dashboard cargado exitosamente', 'success');
            });

            Livewire.on('toast', (event) => {
                console.log('📢 Toast event received:', event);
                if (event.message && event.type) {
                    showToast(event.message, event.type);
                }
            });
        });

        // Enhanced toast notification function
        function showToast(message, type = 'success') {
            // Remove existing toasts
            document.querySelectorAll('.toast-notification').forEach(toast => {
                toast.remove();
            });

            const toast = document.createElement('div');
            toast.className = `toast-notification fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-0 ${
                type === 'success' ? 'bg-green-500 text-white' : 
                type === 'error' ? 'bg-red-500 text-white' : 
                type === 'warning' ? 'bg-yellow-500 text-white' : 
                type === 'info' ? 'bg-blue-500 text-white' :
                'bg-gray-500 text-white'
            }`;
            
            toast.innerHTML = `
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        ${type === 'success' ? '✅' : type === 'error' ? '❌' : type === 'warning' ? '⚠️' : type === 'info' ? 'ℹ️' : '📢'}
                    </div>
                    <div class="flex-1 text-sm font-medium">${message}</div>
                    <button onclick="this.parentElement.parentElement.remove()" class="flex-shrink-0 text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            // Auto-remove after delay
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.style.transform = 'translateX(100%)';
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }
            }, type === 'error' ? 8000 : 5000); // Keep errors visible longer
        }

        // Add loading progress indicator
        function updateLoadingProgress(step, total, message) {
            const loadingEl = document.getElementById('dashboard-loading');
            if (loadingEl && !loadingEl.classList.contains('hidden')) {
                const progressPercent = Math.round((step / total) * 100);
                loadingEl.innerHTML = `
                    <div class="text-center py-20 fade-in">
                        <div class="inline-block">
                            <div class="spinner-ring-lg mx-auto mb-6"></div>
                            <div class="space-y-2">
                                <p class="text-lg font-semibold text-gray-900">Cargando tu dashboard...</p>
                                <p class="text-gray-500">${message}</p>
                                <div class="w-64 bg-gray-200 rounded-full h-2 mx-auto mt-4">
                                    <div class="bg-itti-primary h-2 rounded-full transition-all duration-300" style="width: ${progressPercent}%"></div>
                                </div>
                                <p class="text-xs text-gray-400">${progressPercent}%</p>
                            </div>
                        </div>
                    </div>
                `;
            }
        }
    </script>
</div>