<div>
    
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Catálogo de Productos</h1>
        <p class="mt-2 text-gray-600">
            Descubre todos los productos disponibles para canjear con tus puntos
        </p>
    </div>

    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
        <div class="p-6">
            
            <div class="mb-6">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <input type="text" 
                           wire:model.live.debounce.300ms="search"
                           class="form-input pl-10" 
                           placeholder="Buscar productos...">
                </div>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                
                <div>
                    <label class="form-label">Categoría</label>
                    <select wire:model.live="category" class="form-input">
                        <option value="">Todas las categorías</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                
                <div>
                    <label class="form-label">Puntos máximos</label>
                    <input type="number" 
                           wire:model.live="maxPoints"
                           class="form-input" 
                           min="0"
                           max="{{ $employee ? $employee->puntos_totales : 999999 }}"
                           placeholder="Máximo">
                </div>

                
                <div>
                    <label class="form-label">Ordenar por</label>
                    <select wire:model.live="sortBy" class="form-input">
                        <option value="costo_puntos">Precio</option>
                        <option value="nombre">Nombre</option>
                        <option value="categoria">Categoría</option>
                        <option value="created_at">Más recientes</option>
                    </select>
                </div>

                
                <div>
                    <label class="form-label">Dirección</label>
                    <select wire:model.live="sortDirection" class="form-input">
                        <option value="asc">Ascendente</option>
                        <option value="desc">Descendente</option>
                    </select>
                </div>

                
                <div class="flex items-end space-x-2">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               wire:model.live="availableOnly"
                               class="rounded border-gray-300 text-teal-600 shadow-sm focus:border-teal-300 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-600">Solo disponibles</span>
                    </label>
                </div>
            </div>

            
            <div class="mt-4 pt-4 border-t border-gray-200">
                <button wire:click="clearFilters" class="btn-secondary btn-sm">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                    </svg>
                    Limpiar filtros
                </button>
            </div>
        </div>
    </div>

    
    <div class="mb-6">
        <p class="text-sm text-gray-600">
            Mostrando {{ $products->count() }} de {{ $products->total() }} productos
        </p>
    </div>

    
    @if($products->count() > 0)
        <div class="product-grid mb-8">
            @foreach($products as $product)
                <div class="product-card fade-in">
                    
                    <div class="product-card-image">
                        <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                        </svg>
                    </div>

                    <div class="product-card-content">
                        
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="product-card-title">{{ $product->nombre }}</h3>
                            @if($this->isUserAuthenticated)
                                <button wire:click="toggleFavorite({{ $product->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="toggleFavorite({{ $product->id }})"
                                        class="flex-shrink-0 p-1 rounded-full hover:bg-gray-100 transition-colors">
                                    <div wire:loading.remove wire:target="toggleFavorite({{ $product->id }})">
                                        <svg class="w-5 h-5 {{ in_array($product->id, $favoriteIds) ? 'text-red-500' : 'text-gray-300' }}" 
                                             fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div wire:loading wire:target="toggleFavorite({{ $product->id }})">
                                        <div class="w-5 h-5 border-2 border-gray-300 border-t-red-500 rounded-full animate-spin"></div>
                                    </div>
                                </button>
                            @elseif($isAuthenticated)
                                
                                <div class="flex-shrink-0 p-1 rounded-full">
                                    <div class="w-5 h-5 border-2 border-gray-300 border-t-gray-500 rounded-full animate-spin"></div>
                                </div>
                            @else
                                
                                <div class="flex-shrink-0 p-1 rounded-full">
                                    <svg class="w-5 h-5 text-gray-300" 
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        
                        @if($product->descripcion)
                            <p class="product-card-description">{{ $product->descripcion }}</p>
                        @endif

                        
                        @if($product->categoria)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800 mb-3">
                                {{ $product->categoria }}
                            </span>
                        @endif

                        
                        @if($product->stock !== -1)
                            <p class="text-xs text-gray-500 mb-2">
                                Stock disponible: {{ $product->stock }} unidades
                            </p>
                        @endif

                        
                        <div class="flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="product-card-price">{{ number_format($product->costo_puntos) }} puntos</span>
                                @if($employee && $product->costo_puntos > $employee->puntos_totales)
                                    <span class="text-xs text-red-500">Puntos insuficientes</span>
                                @endif
                            </div>
                            
                            <button wire:click="redirectToProduct({{ $product->id }})"
                                    class="btn-primary btn-sm">
                                Ver detalles
                            </button>
                        </div>
                    </div>

                    
                    @if(!$product->is_available)
                        <div class="absolute inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center rounded-lg">
                            <span class="text-white font-medium">No disponible</span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        
        <div class="flex justify-center">
            {{ $products->links() }}
        </div>
    @else
        
        <div class="text-center py-12">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8l-4 4m0 0l-4-4m4 4V3"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No se encontraron productos</h3>
            <p class="mt-2 text-gray-500">
                @if($search || $category || ($employee && $maxPoints < $employee->puntos_totales))
                    Intenta ajustar los filtros de búsqueda.
                @else
                    No hay productos disponibles en este momento.
                @endif
            </p>
            @if($search || $category || ($employee && $maxPoints < $employee->puntos_totales))
                <div class="mt-6">
                    <button wire:click="clearFilters" class="btn-primary">
                        Limpiar filtros
                    </button>
                </div>
            @endif
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
            console.log('ProductCatalog: Received Livewire favorite-toggled event:', event);
            // Convert Livewire event to browser event for cross-component sync
            window.dispatchEvent(new CustomEvent('favorite-toggled', {
                detail: event
            }));
        });

        // Listen for browser favorite updates from other components/pages
        window.addEventListener('favorite-toggled', (event) => {
            console.log('ProductCatalog: Received browser favorite-toggled event:', event.detail);
            // Only refresh if this event didn't originate from this component
            if (event.detail.source !== 'product-catalog') {
                @this.forceRefresh();
            }
        });

        // Listen for Livewire events to load employee data
        Livewire.on('load-employee-data', async () => {
            console.log('ProductCatalog: Loading employee data from API');
            await loadEmployeeDataFromAPI();
        });

        // Listen for employee-loaded event to update UI directly
        Livewire.on('employee-loaded', (event) => {
            console.log('ProductCatalog: Employee loaded event received:', event);
            // Force update the UI elements directly as fallback
            setTimeout(() => {
                updateProductCatalogUI();
            }, 100);
        });

        // Listen for authentication completion
        window.addEventListener('auth-completed', (event) => {
            console.log('ProductCatalog: Auth completed, loading employee data');
            setTimeout(() => {
                loadEmployeeDataFromAPI();
                // Also update UI directly as fallback
                setTimeout(() => {
                    updateProductCatalogUI();
                }, 500);
            }, 100);
        });

        // Check if auth is already completed
        if (window.authInitialized && localStorage.getItem('firebase_token')) {
            console.log('ProductCatalog: Auth already initialized, loading employee data');
            setTimeout(() => {
                loadEmployeeDataFromAPI();
                // Also update UI directly as fallback
                setTimeout(() => {
                    updateProductCatalogUI();
                }, 500);
            }, 100);
        }
    });

    // Function to load employee data from API
    async function loadEmployeeDataFromAPI() {
        try {
            const token = localStorage.getItem('firebase_token');
            if (!token) {
                console.log('ProductCatalog: No token available for employee data loading');
                return;
            }

            console.log('ProductCatalog: Fetching employee data from API...');
            const response = await fetch('/api/me', {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('ProductCatalog: Employee data received:', data);

            if (data.success && data.employee) {
                // Call Livewire method to set employee data
                console.log('ProductCatalog: Setting employee data:', data.employee);
                
                // Use the setEmployeeData method to properly handle the data
                @this.call('setEmployeeData', data.employee);
                
                console.log('ProductCatalog: Employee data set successfully');
            }
        } catch (error) {
            console.error('ProductCatalog: Error loading employee data:', error);
        }
    }

    // Function to update the product catalog UI directly
    async function updateProductCatalogUI() {
        try {
            const token = localStorage.getItem('firebase_token');
            if (!token) {
                console.log('ProductCatalog: No token for UI update');
                return;
            }

            console.log('ProductCatalog: Updating UI directly...');

            // Get user's favorites from API
            const response = await fetch('/api/favoritos', {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                console.log('ProductCatalog: Failed to get favorites for UI update');
                return;
            }

            const data = await response.json();
            console.log('ProductCatalog: Favorites data for UI update:', data);

            if (data.success && data.favorites) {
                const favoriteProductIds = data.favorites.map(fav => fav.producto_id);
                console.log('ProductCatalog: Favorite product IDs:', favoriteProductIds);

                // Update all hearts in the product grid
                updateAllHearts(favoriteProductIds);
            }
        } catch (error) {
            console.error('ProductCatalog: Error updating UI directly:', error);
        }
    }

    function updateAllHearts(favoriteProductIds) {
        // Find all product cards
        const productCards = document.querySelectorAll('.product-card');
        console.log('ProductCatalog: Found', productCards.length, 'product cards');

        productCards.forEach((card, index) => {
            const heartContainer = card.querySelector('.flex.items-start.justify-between .flex-shrink-0');
            
            if (!heartContainer) {
                console.log('ProductCatalog: No heart container found in card', index);
                return;
            }

            // Try to extract product ID from any wire:click attribute
            const button = heartContainer.querySelector('button[wire\\:click*="toggleFavorite"]');
            let productId = null;
            
            if (button) {
                const wireClick = button.getAttribute('wire:click');
                const match = wireClick.match(/toggleFavorite\((\d+)\)/);
                if (match) {
                    productId = parseInt(match[1]);
                }
            }

            if (!productId) {
                console.log('ProductCatalog: Could not extract product ID from card', index);
                return;
            }

            console.log('ProductCatalog: Processing product', productId);

            const isFavorite = favoriteProductIds.includes(productId);
            
            // Replace the heart container content with clickable button
            heartContainer.innerHTML = `
                <button onclick="toggleFavoriteDirectly(${productId}, this)" 
                        class="flex-shrink-0 p-1 rounded-full hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5 ${isFavorite ? 'text-red-500' : 'text-gray-300'}" 
                         fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    </svg>
                </button>
            `;

            console.log('ProductCatalog: Updated heart for product', productId, 'isFavorite:', isFavorite);
        });
    }

    // Direct favorite toggle function
    window.toggleFavoriteDirectly = async function(productId, buttonElement) {
        try {
            const token = localStorage.getItem('firebase_token');
            if (!token) {
                window.showToast('Debes iniciar sesión para agregar favoritos', 'warning');
                return;
            }

            // Show loading state
            const svg = buttonElement.querySelector('svg');
            const originalClasses = svg.className;
            svg.className = 'w-5 h-5 text-gray-400 animate-pulse';
            buttonElement.disabled = true;

            console.log('ProductCatalog: Direct toggle for product', productId);

            const response = await fetch(`/api/favoritos/${productId}`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('ProductCatalog: Direct toggle response:', data);

            if (data.success) {
                // Update heart color
                svg.className = data.added ? 'w-5 h-5 text-red-500' : 'w-5 h-5 text-gray-300';
                
                // Show message
                const message = data.added ? 'Producto agregado a favoritos' : 'Producto removido de favoritos';
                window.showToast(message, data.added ? 'success' : 'info');

                // Dispatch browser event for cross-page sync
                window.dispatchEvent(new CustomEvent('favorite-toggled', {
                    detail: {
                        productId: productId,
                        isFavorite: data.added,
                        source: 'product-catalog-direct'
                    }
                }));
            } else {
                throw new Error(data.message || 'Error al actualizar favoritos');
            }

        } catch (error) {
            console.error('ProductCatalog: Direct toggle error:', error);
            window.showToast('Error al actualizar favoritos', 'error');
            
            // Restore original state
            const svg = buttonElement.querySelector('svg');
            svg.className = originalClasses;
        } finally {
            buttonElement.disabled = false;
        }
    }
</script>
@endpush