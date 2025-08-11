<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">Recomendado para ti</h3>
            <div class="flex items-center space-x-2">
                <button wire:click="toggleReasons" 
                        class="text-sm text-gray-500 hover:text-gray-700">
                    <svg class="h-4 w-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $showReasons ? 'Ocultar razones' : 'Ver por qué' }}
                </button>
                <button wire:click="loadRecommendations" 
                        class="text-sm text-teal-600 hover:text-teal-800">
                    <svg class="h-4 w-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Actualizar
                </button>
            </div>
        </div>
    </div>

    <div class="p-6">
        @if($recommendations && $recommendations->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($recommendations as $product)
                    <div class="bg-gray-50 rounded-lg p-4 hover:shadow-md transition-shadow">
                        
                        <div class="w-full h-32 bg-gray-200 rounded-lg mb-3 flex items-center justify-center">
                            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>

                        
                        <div class="space-y-2">
                            <h4 class="font-medium text-gray-900 line-clamp-2">{{ $product->nombre }}</h4>
                            
                            @if($product->descripcion)
                                <p class="text-sm text-gray-500 line-clamp-2">{{ $product->descripcion }}</p>
                            @endif

                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <span class="font-semibold text-teal-600">{{ $product->puntos_requeridos }} pts</span>
                                    @if($product->stock !== null)
                                        <span class="text-xs px-2 py-1 rounded-full {{ $product->stock > 5 ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                            {{ $product->stock > 0 ? $product->stock . ' disponibles' : 'Agotado' }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            
                            @if($showReasons)
                                <div class="text-xs text-gray-500 bg-blue-50 px-2 py-1 rounded">
                                    <svg class="h-3 w-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $this->getRecommendationReason($product->id) }}
                                </div>
                            @endif

                            
                            <div class="flex space-x-2 mt-3">
                                <button wire:click="addToFavorites({{ $product->id }})"
                                        class="flex-1 bg-teal-50 text-teal-700 px-3 py-2 rounded-md text-sm hover:bg-teal-100 transition-colors">
                                    <svg class="h-4 w-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    Favorito
                                </button>
                                <a href="/products/{{ $product->id }}"
                                   class="flex-1 bg-gray-100 text-gray-700 px-3 py-2 rounded-md text-sm hover:bg-gray-200 transition-colors text-center">
                                    Ver detalles
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay recomendaciones disponibles</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Intenta agregar algunos productos a favoritos o realizar compras para obtener recomendaciones personalizadas.
                </p>
                <div class="mt-6">
                    <a href="/products" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Explorar productos
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
