@extends('layouts.app')

@section('title', $product->nombre)

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li>
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            </li>
            <li>
                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            </li>
            <li>
                <a href="{{ route('products.index') }}" class="hover:text-blue-600">Productos</a>
            </li>
            <li>
                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            </li>
            <li class="text-gray-900 font-medium">{{ $product->nombre }}</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <div class="space-y-4">
            <div class="aspect-square bg-gray-200 rounded-lg flex items-center justify-center">
                @if($product->imagen_url)
                    <img src="{{ $product->imagen_url }}" alt="{{ $product->nombre }}" class="w-full h-full object-cover rounded-lg">
                @else
                    <div class="text-center text-gray-500">
                        <svg class="w-24 h-24 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-lg font-medium">{{ $product->nombre }}</p>
                    </div>
                @endif
            </div>
        </div>

        
        <div class="space-y-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->nombre }}</h1>
                
                
                @if($product->categoria)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ $product->categoria }}
                    </span>
                @endif
            </div>

            
            <div class="text-4xl font-bold text-blue-600">
                {{ number_format($product->puntos_requeridos) }} puntos
            </div>

            
            @if($product->descripcion)
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Descripción</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $product->descripcion }}</p>
                </div>
            @endif

            
            <div class="flex items-center space-x-4">
                @if($product->stock_limitado && $product->stock_disponible !== null)
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path>
                        </svg>
                        <span class="text-sm text-gray-600">
                            Stock disponible: 
                            <span class="font-medium {{ $product->stock_disponible > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $product->stock_disponible }}
                            </span>
                        </span>
                    </div>
                @else
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-sm text-green-600 font-medium">Stock ilimitado</span>
                    </div>
                @endif

                
                <div class="flex items-center">
                    @if($product->activo)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Disponible
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            No disponible
                        </span>
                    @endif
                </div>
            </div>

            
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Tus puntos disponibles:</span>
                    <span id="user-points-display" class="text-lg font-bold text-gray-900">Cargando...</span>
                </div>
                
                <div id="points-status" class="mt-2" style="display: none;">
                </div>
            </div>

            
            @if($product->activo && (!$product->stock_limitado || $product->stock_disponible > 0))
                <form id="redeem-form" class="space-y-4">
                    @csrf
                    <div>
                        <label for="observaciones" class="form-label">Observaciones (opcional)</label>
                        <textarea 
                            id="observaciones" 
                            name="observaciones" 
                            rows="3" 
                            class="form-input"
                            placeholder="Agregar observaciones sobre el canje..."></textarea>
                    </div>

                    <button 
                        type="submit" 
                        id="redeem-button"
                        class="w-full btn-primary btn-lg"
                        data-product-cost="{{ $product->puntos_requeridos }}">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        Canjear por {{ number_format($product->puntos_requeridos) }} puntos
                    </button>
                </form>
            @else
                <div class="w-full bg-gray-100 text-gray-500 text-center py-4 rounded-lg">
                    @if(!$product->activo)
                        Producto no disponible
                    @else
                        Sin stock disponible
                    @endif
                </div>
            @endif

            
            <div class="flex space-x-4">
                <button 
                    id="favorite-button-{{ $product->id }}"
                    onclick="toggleFavorite({{ $product->id }})"
                    class="btn-ghost flex-1"
                    data-product-id="{{ $product->id }}"
                    data-is-favorite="{{ $isFavorite ? 'true' : 'false' }}">
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2 {{ $isFavorite ? 'text-red-500' : 'text-gray-400' }}" 
                             fill="{{ $isFavorite ? 'currentColor' : 'none' }}" 
                             stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span id="favorite-text-{{ $product->id }}">
                            {{ $isFavorite ? 'Remover de favoritos' : 'Agregar a favoritos' }}
                        </span>
                    </div>
                </button>

                <a href="{{ route('products.index') }}" class="btn-secondary flex-1 text-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver al catálogo
                </a>
            </div>
        </div>
    </div>
</div>


<div id="message-container" class="fixed top-4 right-4 z-50 hidden">
    <div id="message" class="px-6 py-4 rounded-lg shadow-lg text-white"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const redeemForm = document.getElementById('redeem-form');
    const redeemButton = document.getElementById('redeem-button');
    
    // Load correct favorite status after authentication
    loadFavoriteStatus();
    
    // Function to update button state based on auth and points
    async function updateButtonState() {
        const token = localStorage.getItem('firebase_token');
        if (!redeemButton) return;
        
        if (!token) {
            redeemButton.disabled = true;
            redeemButton.className = "w-full btn-secondary btn-lg opacity-50 cursor-not-allowed";
            redeemButton.innerHTML = `
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 0h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                Inicia sesión para canjear
            `;
            return;
        }

        // User is authenticated, check if they have enough points
        try {
            const response = await fetch('/api/me', {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const userData = await response.json();
                const userPoints = userData.employee?.puntos_totales || 0;
                const productCost = parseInt(redeemButton.dataset.productCost) || 0;
                
                console.log('User points:', userPoints, 'Product cost:', productCost);
                
                // Update points display
                const pointsDisplay = document.getElementById('user-points-display');
                if (pointsDisplay) {
                    pointsDisplay.textContent = userPoints.toLocaleString();
                }
                
                // Update points status
                const pointsStatus = document.getElementById('points-status');
                if (pointsStatus) {
                    pointsStatus.style.display = 'block';
                    
                    if (userPoints >= productCost) {
                        pointsStatus.innerHTML = `
                            <div class="flex items-center text-sm text-green-600">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Tienes suficientes puntos
                            </div>
                        `;
                    } else {
                        const needed = productCost - userPoints;
                        pointsStatus.innerHTML = `
                            <div class="flex items-center text-sm text-red-600">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                Puntos insuficientes (necesitas ${needed.toLocaleString()} más)
                            </div>
                        `;
                    }
                }
                
                if (userPoints >= productCost) {
                    // User can afford it
                    redeemButton.disabled = false;
                    redeemButton.className = "w-full btn-primary btn-lg";
                    redeemButton.innerHTML = `
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        Canjear por {{ number_format($product->puntos_requeridos) }} puntos
                    `;
                } else {
                    // Insufficient points
                    redeemButton.disabled = true;
                    redeemButton.className = "w-full btn-secondary btn-lg opacity-50 cursor-not-allowed";
                    redeemButton.innerHTML = `
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        Puntos insuficientes (${userPoints.toLocaleString()} de ${productCost.toLocaleString()})
                    `;
                }
            } else {
                // API error, disable button
                redeemButton.disabled = true;
                redeemButton.className = "w-full btn-secondary btn-lg opacity-50";
                redeemButton.innerHTML = `
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    Error al cargar datos
                `;
            }
        } catch (error) {
            console.error('Error checking user data:', error);
            redeemButton.disabled = true;
            redeemButton.className = "w-full btn-secondary btn-lg opacity-50";
        }
    }
    
    // Listen for auth completion
    window.addEventListener('auth-completed', () => {
        console.log('Product details: Auth completed, updating button and loading favorite status');
        updateButtonState();
        setTimeout(() => {
            loadFavoriteStatus();
        }, 100);
    });
    
    // Check if auth is already completed
    if (window.authInitialized && localStorage.getItem('firebase_token')) {
        setTimeout(() => {
            updateButtonState();
            loadFavoriteStatus();
        }, 500);
    } else {
        // Set initial state and keep checking for auth
        updateButtonState();
        
        // Keep checking for authentication
        let authCheckCount = 0;
        const authCheckInterval = setInterval(() => {
            authCheckCount++;
            const token = localStorage.getItem('firebase_token');
            
            if (token || authCheckCount > 20) { // Stop after 10 seconds
                clearInterval(authCheckInterval);
                if (token) {
                    updateButtonState();
                    loadFavoriteStatus();
                }
            }
        }, 500);
    }
    
    if (redeemForm) {
        console.log('Redemption form found, attaching event listener');
        
        redeemForm.addEventListener('submit', async function(e) {
            console.log('Form submission started!');
            e.preventDefault();
            
            // Check if user is authenticated
            const token = localStorage.getItem('firebase_token');
            console.log('Firebase token:', token ? 'Present' : 'Missing');
            
            if (!token) {
                showMessage('Debes iniciar sesión para canjear productos', 'error');
                return;
            }
            
            // Show loading state
            const originalText = redeemButton.innerHTML;
            redeemButton.disabled = true;
            redeemButton.innerHTML = '<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"></div>Procesando...';
            
            try {
                const formData = new FormData(redeemForm);
                const observaciones = formData.get('observaciones') || '';
                console.log('Making redemption request...');
                console.log('URL:', `/api/productos/{{ $product->id }}/canjear`);
                console.log('Observaciones:', observaciones);
                
                const requestBody = {
                    observaciones: observaciones
                };
                
                console.log('Request body:', requestBody);
                
                const response = await fetch(`/api/productos/{{ $product->id }}/canjear`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(requestBody)
                });
                
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                
                let data;
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    data = await response.json();
                } else {
                    const text = await response.text();
                    console.log('Non-JSON response:', text);
                    throw new Error(`Server returned non-JSON response: ${text.substring(0, 200)}`);
                }
                
                console.log('Response data:', data);
                
                if (data.success) {
                    // Show simple, clear success message
                    showMessage('✅ ¡Producto canjeado exitosamente!', 'success');
                    console.log('Redemption successful! Order data:', data);
                    
                    // Update button to show completion
                    redeemButton.innerHTML = `
                        <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        ¡Canjeado exitosamente!
                    `;
                    redeemButton.className = "w-full btn-success btn-lg";
                    redeemButton.disabled = true;
                    
                    // Redirect to orders after 2 seconds
                    setTimeout(() => {
                        window.location.href = '{{ route("orders.history") }}';
                    }, 2000);
                } else {
                    showMessage(data.message || 'Error al procesar el canje', 'error');
                    redeemButton.disabled = false;
                    redeemButton.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Redemption error:', error);
                showMessage(`Error al procesar el canje: ${error.message}`, 'error');
                redeemButton.disabled = false;
                redeemButton.innerHTML = originalText;
            }
        });
    } else {
        console.log('Redemption form NOT found!');
    }
    
    // Backup: Also add click handler directly to button
    if (redeemButton) {
        console.log('Adding backup click handler to button');
        redeemButton.addEventListener('click', function(e) {
            console.log('Button clicked directly!');
            // Let the form submission handle it, but log that button was clicked
        });
    }
});

async function toggleFavorite(productId) {
    // Get UI elements first
    const button = document.getElementById(`favorite-button-${productId}`);
    const svg = button.querySelector('svg');
    const text = document.getElementById(`favorite-text-${productId}`);
    const originalIsFavorite = button.getAttribute('data-is-favorite') === 'true';
    
    try {
        console.log('Product details: toggleFavorite called with productId:', productId);
        
        // Check if user is authenticated by checking for Firebase token
        const token = localStorage.getItem('firebase_token');
        if (!token) {
            console.log('Product details: No Firebase token found');
            showMessage('Debes iniciar sesión para agregar favoritos', 'error');
            return;
        }

        console.log('Product details: Token found, making API call...');
        
        // Show loading state
        button.disabled = true;
        svg.className = 'w-5 h-5 mr-2 text-gray-400 animate-pulse';
        text.textContent = 'Procesando...';

        // Make direct API call with fetch
        const response = await fetch(`/api/favoritos/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        });

        console.log('Product details: Fetch response status:', response.status);

        if (!response.ok) {
            console.error('Product details: Response not ok:', response.status, response.statusText);
            if (response.status === 401) {
                // Token expired, clear it and redirect to login
                localStorage.removeItem('firebase_token');
                window.location.href = '/login';
                return;
            }
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        console.log('Product details: API response data:', data);

        if (data && data.success) {
            // Update UI state
            const isNowFavorite = data.added;
            button.setAttribute('data-is-favorite', isNowFavorite ? 'true' : 'false');
            
            if (isNowFavorite) {
                svg.className = 'w-5 h-5 mr-2 text-red-500';
                svg.setAttribute('fill', 'currentColor');
                text.textContent = 'Remover de favoritos';
            } else {
                svg.className = 'w-5 h-5 mr-2 text-gray-400';
                svg.setAttribute('fill', 'none');
                text.textContent = 'Agregar a favoritos';
            }
            
            // Show success message
            const message = data.added ? 'Producto agregado a favoritos' : 'Producto removido de favoritos';
            console.log('Product details: Success! Showing message:', message);
            showMessage(message, 'success');
            
            // Dispatch browser event to synchronize with other components
            window.dispatchEvent(new CustomEvent('favorite-toggled', {
                detail: {
                    productId: productId,
                    isFavorite: data.added,
                    source: 'product-details'
                }
            }));
            
            // Set flag for favorites page refresh
            localStorage.setItem('favorites_updated', Date.now().toString());
        } else {
            console.error('Product details: API response indicates failure:', data);
            throw new Error(data?.message || 'Error al actualizar favoritos');
        }

    } catch (error) {
        console.error('Product details: Error in toggleFavorite:', error);
        showMessage('Error al actualizar favoritos', 'error');
        
        // Restore original state on error
        if (originalIsFavorite) {
            svg.className = 'w-5 h-5 mr-2 text-red-500';
            svg.setAttribute('fill', 'currentColor');
            text.textContent = 'Remover de favoritos';
        } else {
            svg.className = 'w-5 h-5 mr-2 text-gray-400';
            svg.setAttribute('fill', 'none');
            text.textContent = 'Agregar a favoritos';
        }
        button.setAttribute('data-is-favorite', originalIsFavorite ? 'true' : 'false');
    } finally {
        // Re-enable button
        button.disabled = false;
    }
}

async function loadFavoriteStatus() {
    try {
        const token = localStorage.getItem('firebase_token');
        if (!token) {
            console.log('Product details: No token, skipping favorite status load');
            return;
        }

        const productId = {{ $product->id }};
        console.log('Product details: Loading favorite status for product:', productId);

        // Get current user's favorites
        const response = await fetch('/api/favoritos', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            console.log('Product details: Failed to load favorites:', response.status);
            return;
        }

        const data = await response.json();
        console.log('Product details: Favorites data received:', data);

        if (data.success && data.favorites) {
            // Check if current product is in favorites
            const isFavorite = data.favorites.some(fav => fav.producto_id == productId);
            console.log('Product details: Is favorite:', isFavorite);
            
            // Update button state
            updateFavoriteButton(productId, isFavorite);
        }
    } catch (error) {
        console.error('Product details: Error loading favorite status:', error);
    }
}

function updateFavoriteButton(productId, isFavorite) {
    const button = document.getElementById(`favorite-button-${productId}`);
    const svg = button?.querySelector('svg');
    const text = document.getElementById(`favorite-text-${productId}`);
    
    if (!button || !svg || !text) {
        console.log('Product details: Button elements not found');
        return;
    }
    
    button.setAttribute('data-is-favorite', isFavorite ? 'true' : 'false');
    
    if (isFavorite) {
        svg.className = 'w-5 h-5 mr-2 text-red-500';
        svg.setAttribute('fill', 'currentColor');
        text.textContent = 'Remover de favoritos';
    } else {
        svg.className = 'w-5 h-5 mr-2 text-gray-400';
        svg.setAttribute('fill', 'none');
        text.textContent = 'Agregar a favoritos';
    }
    
    console.log('Product details: Button updated, isFavorite:', isFavorite);
}

function showMessage(message, type) {
    const container = document.getElementById('message-container');
    const messageEl = document.getElementById('message');
    
    messageEl.textContent = message;
    messageEl.className = `px-6 py-4 rounded-lg shadow-lg text-white ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    
    container.classList.remove('hidden');
    
    setTimeout(() => {
        container.classList.add('hidden');
    }, 5000);
}

function showDetailedMessage(message, type) {
    const container = document.getElementById('message-container');
    const messageEl = document.getElementById('message');
    
    // Create detailed message with proper formatting
    messageEl.innerHTML = message.split('\n').map(line => 
        line.trim() ? `<div class="mb-1">${line.trim()}</div>` : '<div class="mb-2"></div>'
    ).join('');
    
    messageEl.className = `px-6 py-6 rounded-lg shadow-2xl text-white max-w-md ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    
    container.classList.remove('hidden');
    container.style.zIndex = '9999';
    
    // Don't auto-hide detailed success messages since they include important info
    if (type !== 'success') {
        setTimeout(() => {
            container.classList.add('hidden');
        }, 8000);
    }
}
</script>
@endsection