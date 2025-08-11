<div class="space-y-8">
    
    
    <div class="relative fade-in-up">
        <div class="bg-gradient-to-r from-red-50/80 via-pink-50/80 to-red-50/60 rounded-3xl p-8 border border-red-100">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between">
                <div class="flex items-center space-x-6 mb-6 lg:mb-0">
                    
                    <div class="w-20 h-20 bg-gradient-to-br from-red-500 to-pink-500 rounded-3xl flex items-center justify-center shadow-lg hover-glow hover-scale">
                        <svg class="w-10 h-10 text-white animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    
                    
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">
                            Mis Favoritos
                        </h1>
                        <p class="text-lg text-gray-600 mb-3">
                            Tus productos guardados para canjear más tarde
                        </p>
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                {{ number_format($employee ? $employee->puntos_totales : 0) }} puntos disponibles
                            </span>
                            @if($favorites->count() > 0)
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $favorites->count() }} producto{{ $favorites->count() !== 1 ? 's' : '' }} guardados
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                
                @if($favorites->count() > 0)
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-3 bg-white/50 rounded-2xl border border-white/20 hover-float">
                            <p class="text-2xl font-bold text-green-600">{{ number_format($stats['available_favorites']) }}</p>
                            <p class="text-xs text-gray-600">Disponibles</p>
                        </div>
                        <div class="text-center p-3 bg-white/50 rounded-2xl border border-white/20 hover-float">
                            <p class="text-2xl font-bold text-itti-primary">{{ number_format($stats['affordable_favorites']) }}</p>
                            <p class="text-xs text-gray-600">Puedes costear</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        
        <div class="absolute -top-4 -right-4 w-32 h-32 bg-red-200/30 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-pink-200/30 rounded-full blur-3xl"></div>
    </div>

    
    @if($favorites->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <div class="stats-card bg-gradient-to-br from-gray-50 to-slate-50 border-gray-200 hover:scale-105 transition-transform duration-300">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 bg-gradient-to-r from-gray-600 to-gray-800 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_favorites']) }}</div>
                        <div class="text-sm font-medium text-gray-600">Total Favoritos</div>
                    </div>
                </div>
            </div>

            
            <div class="stats-card bg-gradient-to-br from-green-50 to-emerald-50 border-green-200 hover:scale-105 transition-transform duration-300">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-green-600">{{ number_format($stats['available_favorites']) }}</div>
                        <div class="text-sm font-medium text-gray-600">Disponibles</div>
                    </div>
                </div>
            </div>

            
            <div class="stats-card bg-gradient-to-br from-blue-50 to-indigo-50 border-blue-200 hover:scale-105 transition-transform duration-300">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-blue-600">{{ number_format($stats['total_cost']) }}</div>
                        <div class="text-sm font-medium text-gray-600">Costo Total</div>
                    </div>
                </div>
            </div>

            
            <div class="stats-card bg-gradient-to-br from-itti-primary/5 to-itti-secondary/5 border-itti-primary/20 hover:scale-105 transition-transform duration-300">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 bg-gradient-to-r from-itti-primary to-itti-secondary rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-itti-primary">{{ number_format($stats['affordable_favorites']) }}</div>
                        <div class="text-sm font-medium text-gray-600">Puedes Costear</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    
    @if($favorites->count() > 0)
        <div class="card">
            <div class="card-body">
                <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between space-y-4 lg:space-y-0">
                    
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-gradient-to-r from-red-500 to-pink-500 rounded-lg flex items-center justify-center">
                                <span class="text-sm font-bold text-white">{{ $favorites->count() }}</span>
                            </div>
                            <span class="text-lg font-semibold text-gray-900">
                                {{ $favorites->count() }} producto{{ $favorites->count() !== 1 ? 's' : '' }} guardados
                            </span>
                        </div>
                        
                        
                        @if($stats['can_afford_all'])
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                ✨ Puedes costear todos
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-gradient-to-r from-yellow-100 to-orange-100 text-orange-800 border border-orange-200">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Puntos insuficientes para algunos
                            </span>
                        @endif
                    </div>

                    
                    <div class="flex items-center space-x-3">
                        <button wire:click="clearAllFavorites" 
                                onclick="return confirm('¿Estás seguro de que quieres eliminar todos los favoritos?')"
                                class="btn-secondary btn-sm group">
                            <svg class="w-4 h-4 mr-2 group-hover:rotate-12 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                            Limpiar Todo
                        </button>
                        
                        <a href="{{ route('products.index') }}" class="btn-secondary btn-sm group">
                            <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd"/>
                            </svg>
                            Explorar más
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    
    @if($favorites->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach($favorites as $favorite)
                @if($favorite->product)
                    <div class="product-card fade-in group {{ !$favorite->product->is_available ? 'opacity-75' : '' }}">
                        
                        
                        <div class="product-card-image relative overflow-hidden">
                            <div class="w-full h-full bg-gradient-to-br from-gray-100 via-gray-50 to-gray-100 flex items-center justify-center group-hover:from-red-50 group-hover:to-pink-50 transition-all duration-300">
                                <svg class="w-20 h-20 text-gray-400 group-hover:text-red-400 group-hover:scale-110 transition-all duration-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            
                            
                            <div class="absolute top-3 right-3">
                                <button wire:click="removeFavorite({{ $favorite->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="removeFavorite({{ $favorite->id }})"
                                        class="w-10 h-10 bg-white/90 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg hover:bg-white hover:scale-110 transition-all duration-200 {{ $loadingFavoriteId === $favorite->id ? 'cursor-not-allowed opacity-50' : '' }}">
                                    @if($loadingFavoriteId === $favorite->id)
                                        <div class="w-5 h-5 border-2 border-red-300 border-t-red-500 rounded-full animate-spin"></div>
                                    @else
                                        <svg class="w-5 h-5 text-red-500 hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                </button>
                            </div>

                            
                            @if($favorite->product->stock !== -1)
                                <div class="absolute top-3 left-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/90 backdrop-blur-sm text-gray-700 shadow-lg">
                                        <svg class="w-3 h-3 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $favorite->product->stock }} disponibles
                                    </span>
                                </div>
                            @endif

                            
                            <div class="absolute bottom-3 left-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-white/80 backdrop-blur-sm text-gray-600 shadow-sm">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $favorite->fecha_agregado->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>

                        
                        <div class="product-card-content">
                            
                            
                            @if($favorite->product->categoria)
                                <div class="mb-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a1 1 0 011-1h14a1 1 0 110 2H3a1 1 0 01-1-1z"/>
                                        </svg>
                                        {{ $favorite->product->categoria }}
                                    </span>
                                </div>
                            @endif

                            
                            <h3 class="product-card-title group-hover:text-red-600 transition-colors duration-200">
                                {{ $favorite->product->nombre }}
                            </h3>

                            
                            @if($favorite->product->descripcion)
                                <p class="product-card-description">{{ $favorite->product->descripcion }}</p>
                            @endif

                            
                            <div class="mt-6 space-y-4">
                                
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-2xl font-bold text-itti-primary">{{ number_format($favorite->product->costo_puntos) }}</span>
                                        <span class="text-sm text-gray-500 ml-1">puntos</span>
                                    </div>
                                    @if($employee && $favorite->product->costo_puntos > $employee->puntos_totales)
                                        <span class="text-xs text-red-500 bg-red-50 px-2 py-1 rounded-full font-medium">
                                            Puntos insuficientes
                                        </span>
                                    @elseif(!$favorite->product->is_available)
                                        <span class="text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded-full font-medium">
                                            No disponible
                                        </span>
                                    @else
                                        <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded-full font-medium">
                                            ✓ Disponible
                                        </span>
                                    @endif
                                </div>
                                
                                
                                <button wire:click="redirectToProduct({{ $favorite->product->id }})"
                                        class="w-full btn-primary group-hover:shadow-lg">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                    </svg>
                                    Ver detalles
                                </button>
                            </div>
                        </div>

                        
                        @if(!$favorite->product->is_available)
                            <div class="absolute inset-0 bg-gray-900/75 backdrop-blur-sm flex items-center justify-center rounded-2xl">
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span class="text-white font-semibold">No disponible</span>
                                    <p class="text-white/80 text-sm mt-1">Temporalmente agotado</p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    @else
        
        <div class="text-center py-20">
            <div class="mx-auto w-32 h-32 bg-gradient-to-br from-red-100 to-pink-100 rounded-full flex items-center justify-center mb-8 relative">
                <svg class="w-16 h-16 text-red-400 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                
                <div class="absolute -top-2 -right-2 w-6 h-6 bg-pink-300 rounded-full animate-bounce" style="animation-delay: 0.5s;"></div>
                <div class="absolute -bottom-2 -left-2 w-4 h-4 bg-red-300 rounded-full animate-bounce" style="animation-delay: 1s;"></div>
            </div>
            
            <div class="max-w-md mx-auto">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">
                    ¡Aún no tienes favoritos!
                </h3>
                <p class="text-lg text-gray-600 mb-8">
                    Explora nuestro increíble catálogo y guarda los productos que más te gusten. 
                    <span class="font-semibold text-red-500">¡Tu lista de deseos te espera!</span>
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('products.index') }}" class="btn-primary btn-lg group">
                        <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                        Explorar Catálogo
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn-secondary btn-lg group">
                        <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        Volver al Dashboard
                    </a>
                </div>
            </div>
            
            
            <div class="mt-16 p-6 bg-gradient-to-r from-gray-50 to-slate-50 rounded-2xl border border-gray-200 max-w-2xl mx-auto">
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2 text-itti-primary" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    ¿Sabías que...?
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                    <div class="flex items-start space-x-2">
                        <div class="w-5 h-5 bg-itti-primary/20 rounded-full flex items-center justify-center mt-0.5 flex-shrink-0">
                            <div class="w-2 h-2 bg-itti-primary rounded-full"></div>
                        </div>
                        <p>Puedes agregar productos a favoritos con el corazón ❤️ en cada tarjeta</p>
                    </div>
                    <div class="flex items-start space-x-2">
                        <div class="w-5 h-5 bg-itti-primary/20 rounded-full flex items-center justify-center mt-0.5 flex-shrink-0">
                            <div class="w-2 h-2 bg-itti-primary rounded-full"></div>
                        </div>
                        <p>Tus favoritos se sincronizan automáticamente en todos tus dispositivos</p>
                    </div>
                    <div class="flex items-start space-x-2">
                        <div class="w-5 h-5 bg-itti-primary/20 rounded-full flex items-center justify-center mt-0.5 flex-shrink-0">
                            <div class="w-2 h-2 bg-itti-primary rounded-full"></div>
                        </div>
                        <p>Recibe notificaciones cuando los productos favoritos tengan descuentos</p>
                    </div>
                    <div class="flex items-start space-x-2">
                        <div class="w-5 h-5 bg-itti-primary/20 rounded-full flex items-center justify-center mt-0.5 flex-shrink-0">
                            <div class="w-2 h-2 bg-itti-primary rounded-full"></div>
                        </div>
                        <p>Organiza tu lista de deseos para futuras compras y canjes</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        // Listen for toast events from Livewire
        Livewire.on('toast', (event) => {
            if (window.showToast) {
                window.showToast(event.message, event.type);
            }
        });

        // Listen for Livewire favorite-toggled events and convert to browser events
        Livewire.on('favorite-toggled', (event) => {
            console.log('FavoritesList: Received Livewire favorite-toggled event:', event);
            // Convert Livewire event to browser event for cross-component sync
            window.dispatchEvent(new CustomEvent('favorite-toggled', {
                detail: event
            }));
        });

        // Listen for browser favorite updates from other components/pages
        window.addEventListener('favorite-toggled', (event) => {
            console.log('FavoritesList: Received browser favorite-toggled event:', event.detail);
            // Only refresh if this event didn't originate from this component
            if (event.detail.source !== 'favorites-list') {
                setTimeout(() => {
                    window.location.reload();
                }, 100);
            }
        });

        // Listen for authentication completion
        window.addEventListener('auth-completed', (event) => {
            console.log('FavoritesList: Auth completed, reloading employee data');
            @this.loadEmployeeData();
        });
    });

    // Enhanced page load animations and interactions
    document.addEventListener('DOMContentLoaded', function() {
        // Animate cards on load
        const cards = document.querySelectorAll('.product-card, .stats-card, .card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });

        // Add enhanced hover effects
        const productCards = document.querySelectorAll('.product-card');
        productCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
                this.style.boxShadow = '0 20px 40px -5px rgba(0, 0, 0, 0.15)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
                this.style.boxShadow = '';
            });
        });

        // Add bounce animation to favorite buttons
        const favoriteButtons = document.querySelectorAll('button[wire\\:click*="removeFavorite"]');
        favoriteButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'scale(1.1)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 150);
                }, 50);
            });
        });

        // Smooth scroll to top when favorites are cleared
        window.addEventListener('favorites-cleared', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Enhanced empty state animations
        const emptyState = document.querySelector('.text-center.py-20');
        if (emptyState) {
            // Add floating animation to decorative elements
            const floatingElements = emptyState.querySelectorAll('.animate-bounce');
            floatingElements.forEach((element, index) => {
                element.style.animationDelay = `${index * 0.5}s`;
                element.style.animationDuration = `2s`;
            });
        }

        // Check if we already reloaded (to prevent infinite reload loop)
        if (localStorage.getItem('favorites_page_reloaded') === 'true') {
            localStorage.removeItem('favorites_page_reloaded');
            return;
        }
        
        // Enhanced auth check with better UX
        let authCheckInterval;
        let hasReloaded = false;
        
        authCheckInterval = setInterval(function() {
            const token = localStorage.getItem('firebase_token');
            const authInitialized = window.authInitialized;
            
            if (token && authInitialized && !hasReloaded) {
                console.log('Favorites page: Auth detected, reloading to show data...');
                hasReloaded = true;
                clearInterval(authCheckInterval);
                
                // Show loading indicator
                if (window.showToast) {
                    window.showToast('Cargando favoritos...', 'info');
                }
                
                // Set flag to prevent infinite reload
                localStorage.setItem('favorites_page_reloaded', 'true');
                
                // Reload page with smooth transition
                setTimeout(() => {
                    window.location.reload();
                }, 300);
            }
        }, 500);
        
        // Stop checking after 10 seconds
        setTimeout(function() {
            if (authCheckInterval) {
                clearInterval(authCheckInterval);
            }
        }, 10000);
    });

    // Enhanced visibility change detection
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            const lastUpdate = localStorage.getItem('favorites_updated');
            const checkedUpdate = localStorage.getItem('favorites_last_checked');
            
            if (lastUpdate && lastUpdate !== checkedUpdate) {
                console.log('Favorites: Detected update on page focus, reloading...');
                localStorage.setItem('favorites_last_checked', lastUpdate);
                
                // Show subtle notification before reload
                if (window.showToast) {
                    window.showToast('Actualizando favoritos...', 'info');
                }
                
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            }
        }
    });

    // Add custom CSS for enhanced animations
    const style = document.createElement('style');
    style.textContent = `
        .product-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .stats-card {
            transition: all 0.2s ease-out;
            cursor: pointer;
        }
        
        .stats-card:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .btn-primary, .btn-secondary, .btn-danger {
            transition: all 0.2s ease-out;
        }
        
        .btn-primary:hover, .btn-secondary:hover {
            transform: translateY(-1px);
        }
        
        .btn-danger:hover {
            transform: translateY(-1px) rotate(1deg);
        }
        
        .product-card-image {
            transition: all 0.3s ease-out;
        }
        
        .product-card:hover .product-card-image {
            transform: scale(1.05);
        }
        
        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .animate-heartbeat {
            animation: heartbeat 1.5s ease-in-out infinite;
        }
        
        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    `;
    document.head.appendChild(style);
</script>
@endpush