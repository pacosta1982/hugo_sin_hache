<div class="space-y-8">
    
    
    <div class="relative fade-in-up">
        <div class="bg-gradient-to-r from-itti-primary/10 via-itti-secondary/10 to-itti-primary/5 rounded-3xl p-8 border border-itti-primary/20">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-16 h-16 bg-gradient-to-r from-itti-primary to-itti-secondary rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5zM10 12a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Catálogo de Productos</h1>
                            <p class="text-gray-600 mt-1">
                                Descubre productos increíbles para canjear con tus puntos
                            </p>
                        </div>
                    </div>
                    
                    
                    <div class="flex flex-wrap gap-6 mt-6">
                        <div class="flex items-center space-x-2">
                            <div class="w-10 h-10 bg-white/50 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-itti-primary" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-900">{{ number_format(auth()->user()->puntos_totales ?? 0) }}</p>
                                <p class="text-xs text-gray-600">Puntos disponibles</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-10 h-10 bg-white/50 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-itti-primary" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-900">{{ count($favoriteIds ?? []) }}</p>
                                <p class="text-xs text-gray-600">Favoritos</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                <div class="hidden lg:block">
                    <div class="w-32 h-32 bg-gradient-to-br from-itti-primary/20 to-itti-secondary/20 rounded-full flex items-center justify-center">
                        <svg class="w-16 h-16 text-itti-primary" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    
    <div class="card interactive-card fade-in-left">
        <div class="card-header">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Buscar y Filtrar</h3>
                    <p class="text-sm text-gray-500">Encuentra el producto perfecto</p>
                </div>
            </div>
        </div>
        <div class="card-body space-y-6">
            
            
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <input type="text" 
                       wire:model.live.debounce.300ms="search"
                       class="form-input pl-12 text-lg h-14" 
                       placeholder="Buscar productos por nombre, descripción o categoría...">
                <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                    <div wire:loading wire:target="search" class="animate-spin rounded-full h-5 w-5 border-2 border-itti-primary/20 border-t-itti-primary"></div>
                </div>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                
                
                <div class="form-group">
                    <label class="form-label flex items-center">
                        <svg class="w-4 h-4 mr-2 text-itti-primary" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a1 1 0 011-1h14a1 1 0 110 2H3a1 1 0 01-1-1z"/>
                        </svg>
                        Categoría
                    </label>
                    <select wire:model.live="categoryId" class="form-input">
                        <option value="">🗂️ Todas las categorías</option>
                        @foreach($categories as $category)
                            <option value="{{ $category['id'] }}">📁 {{ $category['name'] }}</option>
                            @if(!empty($category['children']))
                                @foreach($category['children'] as $subCategory)
                                    <option value="{{ $subCategory['id'] }}">┗ 📂 {{ $subCategory['name'] }}</option>
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                </div>

                
                <div class="form-group">
                    <label class="form-label flex items-center">
                        <svg class="w-4 h-4 mr-2 text-itti-primary" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        Puntos máximos
                    </label>
                    <input type="number" 
                           wire:model.live="maxPoints"
                           class="form-input" 
                           min="0"
                           max="{{ $employee ? (is_array($employee) ? $employee['puntos_totales'] : $employee->puntos_totales) : 999999 }}"
                           placeholder="Límite de puntos">
                </div>

                
                <div class="form-group">
                    <label class="form-label flex items-center">
                        <svg class="w-4 h-4 mr-2 text-itti-primary" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                        </svg>
                        Ordenar por
                    </label>
                    <select wire:model.live="sortBy" class="form-input">
                        <option value="puntos_requeridos">💰 Precio (puntos)</option>
                        <option value="nombre">🔤 Nombre</option>
                        <option value="created_at">🆕 Más recientes</option>
                    </select>
                </div>

                
                <div class="form-group">
                    <label class="form-label flex items-center">
                        <svg class="w-4 h-4 mr-2 text-itti-primary" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"/>
                        </svg>
                        Dirección
                    </label>
                    <select wire:model.live="sortDirection" class="form-input">
                        <option value="asc">⬆️ Ascendente</option>
                        <option value="desc">⬇️ Descendente</option>
                    </select>
                </div>
            </div>

            
            <div class="flex flex-wrap items-center gap-4 pt-4 border-t border-gray-200">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" 
                           wire:model.live="availableOnly"
                           class="form-checkbox w-5 h-5">
                    <span class="text-sm font-medium text-gray-700 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Solo productos disponibles
                    </span>
                </label>
                
                <button wire:click="clearFilters" class="btn-ghost btn-sm">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                    </svg>
                    Limpiar filtros
                </button>
            </div>
        </div>
    </div>

    
    
    <div class="flex items-center justify-between mb-6 px-1 fade-in-right">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-gradient-to-r from-itti-primary to-itti-secondary rounded-lg flex items-center justify-center">
                <span class="text-sm font-bold text-white">{{ $products->count() }}</span>
            </div>
            <div>
                <p class="text-lg font-semibold text-gray-900">
                    Mostrando {{ $products->count() }} de {{ $products->total() }} productos
                </p>
                <p class="text-sm text-gray-500">
                    @if($search || $categoryId || $maxPoints)
                        Resultados filtrados
                    @else
                        Catálogo completo
                    @endif
                </p>
            </div>
        </div>
        
        
        <div class="flex items-center space-x-2 bg-gray-100 rounded-xl p-1">
            <button class="p-2 bg-white text-itti-primary rounded-lg shadow-sm">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
            </button>
            <button class="p-2 text-gray-400">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    </div>

    
    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 mb-8">
            @foreach($products as $product)
                <div class="product-card stagger-item interactive-card hover-float group flex flex-col h-full">
                    
                    
                    <div class="product-card-image relative overflow-hidden flex-shrink-0">
                        <div class="w-full h-full bg-gradient-to-br from-gray-100 via-gray-50 to-gray-100 flex items-center justify-center group-hover:from-itti-primary/5 group-hover:to-itti-secondary/5 transition-all duration-300">
                            <svg class="w-20 h-20 text-gray-400 group-hover:text-itti-primary group-hover:scale-110 transition-all duration-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        
                        
                        <div class="absolute top-3 right-3">
                            @if($this->isUserAuthenticated)
                                <button wire:click="toggleFavorite({{ $product->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="toggleFavorite({{ $product->id }})"
                                        class="w-10 h-10 bg-white/90 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg hover:bg-white hover:scale-110 transition-all duration-200">
                                    <div wire:loading.remove wire:target="toggleFavorite({{ $product->id }})">
                                        <svg class="w-5 h-5 {{ in_array($product->id, $favoriteIds) ? 'text-red-500' : 'text-gray-400' }}" 
                                             fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div wire:loading wire:target="toggleFavorite({{ $product->id }})">
                                        <div class="w-5 h-5 border-2 border-itti-primary/20 border-t-itti-primary rounded-full animate-spin"></div>
                                    </div>
                                </button>
                            @elseif($isAuthenticated)
                                <div class="w-10 h-10 bg-white/90 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg">
                                    <div class="w-5 h-5 border-2 border-gray-300 border-t-gray-500 rounded-full animate-spin"></div>
                                </div>
                            @else
                                <div class="w-10 h-10 bg-white/90 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        
                        @if($product->stock !== -1)
                            <div class="absolute top-3 left-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/90 backdrop-blur-sm text-gray-700 shadow-lg">
                                    <svg class="w-3 h-3 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $product->stock }} disponibles
                                </span>
                            </div>
                        @endif
                    </div>

                    
                    <div class="product-card-content flex flex-col flex-grow">
                        
                        
                        @if($product->category)
                            <div class="mb-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold" 
                                      style="background-color: {{ $product->category->color ?? '#0ADD90' }}15; color: {{ $product->category->color ?? '#0ADD90' }}">
                                    @if($product->category->icon)
                                        <i class="fas fa-{{ $product->category->icon }} mr-1"></i>
                                    @endif
                                    {{ $product->category->name }}
                                </span>
                            </div>
                        @endif

                        
                        <h3 class="product-card-title group-hover:text-itti-primary transition-colors duration-200">
                            {{ $product->nombre }}
                        </h3>

                        
                        <div class="flex-grow">
                            @if($product->descripcion)
                                <p class="product-card-description">{{ $product->descripcion }}</p>
                            @endif
                        </div>

                        
                        <div class="mt-6 space-y-4">
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-2xl font-bold text-itti-primary">{{ number_format($product->puntos_requeridos) }}</span>
                                    <span class="text-sm text-gray-500 ml-1">puntos</span>
                                </div>
                                @if($employee && $product->puntos_requeridos > (is_array($employee) ? $employee['puntos_totales'] : $employee->puntos_totales))
                                    <span class="text-xs text-red-500 bg-red-50 px-2 py-1 rounded-full font-medium">
                                        Puntos insuficientes
                                    </span>
                                @elseif($employee)
                                    <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded-full font-medium">
                                        ✓ Disponible
                                    </span>
                                @endif
                            </div>
                            
                            
                            <button wire:click="redirectToProduct({{ $product->id }})"
                                    class="w-full btn-primary btn-press ripple group-hover:shadow-lg">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                </svg>
                                Ver detalles
                            </button>
                        </div>
                    </div>

                    
                    @if(!$product->is_available)
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
            @endforeach
        </div>

        
        
        <div class="flex justify-center">
            {{ $products->links() }}
        </div>
    @else
        
        
        <div class="text-center py-20 fade-in scale-in">
            <div class="mx-auto w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-8">
                <svg class="w-16 h-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8l-4 4m0 0l-4-4m4 4V3"/>
                </svg>
            </div>
            
            <div class="max-w-sm mx-auto">
                <h3 class="text-xl font-bold text-gray-900 mb-3">
                    @if($search || $categoryId || ($employee && $maxPoints < (is_array($employee) ? $employee['puntos_totales'] : $employee->puntos_totales)))
                        No se encontraron productos
                    @else
                        No hay productos disponibles
                    @endif
                </h3>
                <p class="text-gray-500 mb-8">
                    @if($search || $categoryId || ($employee && $maxPoints < (is_array($employee) ? $employee['puntos_totales'] : $employee->puntos_totales)))
                        No encontramos productos que coincidan con tus filtros. Intenta ajustar los criterios de búsqueda.
                    @else
                        Los productos aparecerán aquí cuando estén disponibles. Vuelve pronto para ver las novedades.
                    @endif
                </p>
                
                @if($search || $categoryId || ($employee && $maxPoints < (is_array($employee) ? $employee['puntos_totales'] : $employee->puntos_totales)))
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <button wire:click="clearFilters" class="btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                            </svg>
                            Limpiar filtros
                        </button>
                        <a href="{{ route('favorites.index') }}" class="btn-secondary">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                            </svg>
                            Ver mis favoritos
                        </a>
                    </div>
                @endif
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