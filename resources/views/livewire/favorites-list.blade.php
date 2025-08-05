<div>
    
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Mis Favoritos</h1>
        <p class="mt-2 text-gray-600">
            Tus productos guardados para canjear más tarde
        </p>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_favorites']) }}</div>
                <div class="text-sm text-gray-500">Total Favoritos</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-green-600">{{ number_format($stats['available_favorites']) }}</div>
                <div class="text-sm text-gray-500">Disponibles</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_cost']) }}</div>
                <div class="text-sm text-gray-500">Costo Total</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <div class="text-2xl font-bold text-purple-600">{{ number_format($stats['affordable_favorites']) }}</div>
                <div class="text-sm text-gray-500">Puedes Costear</div>
            </div>
        </div>
    </div>

    
    @if($favorites->count() > 0)
        <div class="flex justify-between items-center mb-6">
            <div class="text-sm text-gray-600">
                {{ $favorites->count() }} producto{{ $favorites->count() !== 1 ? 's' : '' }} en favoritos
            </div>
            <div class="space-x-2">
                @if($stats['can_afford_all'])
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Puedes costear todos
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Puntos insuficientes para todos
                    </span>
                @endif

                <button wire:click="clearAllFavorites" 
                        onclick="return confirm('¿Estás seguro de que quieres eliminar todos los favoritos?')"
                        class="btn-danger btn-sm">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"/>
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    Limpiar Todo
                </button>
            </div>
        </div>
    @endif

    
    @if($favorites->count() > 0)
        <div class="product-grid">
            @foreach($favorites as $favorite)
                @if($favorite->product)
                    <div class="product-card fade-in {{ !$favorite->product->is_available ? 'opacity-75' : '' }}">
                        
                        <div class="product-card-image {{ !$favorite->product->is_available ? 'bg-gray-300' : '' }}">
                            <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                            </svg>
                        </div>

                        <div class="product-card-content">
                            
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="product-card-title">{{ $favorite->product->nombre }}</h3>
                                <button wire:click="removeFavorite({{ $favorite->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="removeFavorite({{ $favorite->id }})"
                                        class="flex-shrink-0 p-1 rounded-full hover:bg-gray-100 transition-colors
                                               {{ $loadingFavoriteId === $favorite->id ? 'cursor-not-allowed opacity-50' : '' }}">
                                    @if($loadingFavoriteId === $favorite->id)
                                        <div class="w-5 h-5 border-2 border-red-300 border-t-red-500 rounded-full animate-spin"></div>
                                    @else
                                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                </button>
                            </div>

                            
                            @if($favorite->product->descripcion)
                                <p class="product-card-description">{{ $favorite->product->descripcion }}</p>
                            @endif

                            
                            @if($favorite->product->categoria)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mb-3">
                                    {{ $favorite->product->categoria }}
                                </span>
                            @endif

                            
                            @if($favorite->product->stock !== -1)
                                <p class="text-xs text-gray-500 mb-2">
                                    Stock disponible: {{ $favorite->product->stock }} unidades
                                </p>
                            @endif

                            
                            <p class="text-xs text-gray-400 mb-3">
                                Agregado: {{ $favorite->fecha_agregado->format('d/m/Y') }}
                            </p>

                            
                            <div class="flex items-center justify-between">
                                <div class="flex flex-col">
                                    <span class="product-card-price">{{ number_format($favorite->product->costo_puntos) }} puntos</span>
                                    @if($favorite->product->costo_puntos > $employee->puntos_totales)
                                        <span class="text-xs text-red-500">Puntos insuficientes</span>
                                    @elseif(!$favorite->product->is_available)
                                        <span class="text-xs text-gray-500">No disponible</span>
                                    @else
                                        <span class="text-xs text-green-500">Disponible</span>
                                    @endif
                                </div>
                                
                                <button wire:click="redirectToProduct({{ $favorite->product->id }})"
                                        class="btn-primary btn-sm">
                                    Ver detalles
                                </button>
                            </div>
                        </div>

                        
                        @if(!$favorite->product->is_available)
                            <div class="absolute inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center rounded-lg">
                                <span class="text-white font-medium">No disponible</span>
                            </div>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    @else
        
        <div class="text-center py-12">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No tienes favoritos aún</h3>
            <p class="mt-2 text-gray-500">
                Explora el catálogo y agrega productos a tus favoritos para encontrarlos fácilmente.
            </p>
            <div class="mt-6">
                <a href="{{ route('products.index') }}" class="btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                    Explorar Productos
                </a>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        // Listen for toast events from Livewire
        Livewire.on('toast', (event) => {
            window.showToast(event.message, event.type);
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

    // Simple solution: Reload page after Firebase auth completes
    let authCheckInterval;
    let hasReloaded = false;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Check if we already reloaded (to prevent infinite reload loop)
        if (localStorage.getItem('favorites_page_reloaded') === 'true') {
            localStorage.removeItem('favorites_page_reloaded');
            return;
        }
        
        // Check for Firebase auth completion every 500ms
        authCheckInterval = setInterval(function() {
            const token = localStorage.getItem('firebase_token');
            const authInitialized = window.authInitialized;
            
            if (token && authInitialized && !hasReloaded) {
                console.log('Favorites page: Auth detected, reloading to show data...');
                hasReloaded = true;
                clearInterval(authCheckInterval);
                
                // Set flag to prevent infinite reload
                localStorage.setItem('favorites_page_reloaded', 'true');
                
                // Reload page to show authenticated data
                window.location.reload();
            }
        }, 500);
        
        // Stop checking after 10 seconds
        setTimeout(function() {
            if (authCheckInterval) {
                clearInterval(authCheckInterval);
            }
        }, 10000);
    });

    // Check for favorites updates when returning to page
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            const lastUpdate = localStorage.getItem('favorites_updated');
            const checkedUpdate = localStorage.getItem('favorites_last_checked');
            
            if (lastUpdate && lastUpdate !== checkedUpdate) {
                console.log('Favorites: Detected update on page focus, reloading...');
                localStorage.setItem('favorites_last_checked', lastUpdate);
                window.location.reload();
            }
        }
    });
</script>
@endpush