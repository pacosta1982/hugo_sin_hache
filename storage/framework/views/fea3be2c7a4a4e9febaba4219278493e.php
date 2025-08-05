<?php $__env->startSection('title', $product->nombre); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li>
                <a href="<?php echo e(route('dashboard')); ?>" class="hover:text-blue-600">Dashboard</a>
            </li>
            <li>
                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            </li>
            <li>
                <a href="<?php echo e(route('products.index')); ?>" class="hover:text-blue-600">Productos</a>
            </li>
            <li>
                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            </li>
            <li class="text-gray-900 font-medium"><?php echo e($product->nombre); ?></li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Product Image -->
        <div class="space-y-4">
            <div class="aspect-square bg-gray-200 rounded-lg flex items-center justify-center">
                <?php if($product->imagen_url): ?>
                    <img src="<?php echo e($product->imagen_url); ?>" alt="<?php echo e($product->nombre); ?>" class="w-full h-full object-cover rounded-lg">
                <?php else: ?>
                    <div class="text-center text-gray-500">
                        <svg class="w-24 h-24 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-lg font-medium"><?php echo e($product->nombre); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Details -->
        <div class="space-y-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2"><?php echo e($product->nombre); ?></h1>
                
                <!-- Category Badge -->
                <?php if($product->categoria): ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <?php echo e($product->categoria); ?>

                    </span>
                <?php endif; ?>
            </div>

            <!-- Price -->
            <div class="text-4xl font-bold text-blue-600">
                <?php echo e(number_format($product->costo_puntos)); ?> puntos
            </div>

            <!-- Description -->
            <?php if($product->descripcion): ?>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Descripción</h3>
                    <p class="text-gray-700 leading-relaxed"><?php echo e($product->descripcion); ?></p>
                </div>
            <?php endif; ?>

            <!-- Stock Information -->
            <div class="flex items-center space-x-4">
                <?php if($product->stock_limitado && $product->stock_disponible !== null): ?>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path>
                        </svg>
                        <span class="text-sm text-gray-600">
                            Stock disponible: 
                            <span class="font-medium <?php echo e($product->stock_disponible > 0 ? 'text-green-600' : 'text-red-600'); ?>">
                                <?php echo e($product->stock_disponible); ?>

                            </span>
                        </span>
                    </div>
                <?php else: ?>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-sm text-green-600 font-medium">Stock ilimitado</span>
                    </div>
                <?php endif; ?>

                <!-- Status -->
                <div class="flex items-center">
                    <?php if($product->activo): ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Disponible
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            No disponible
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- User Points -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Tus puntos disponibles:</span>
                    <span class="text-lg font-bold text-gray-900"><?php echo e(number_format($employee ? ($employee->puntos_totales - $employee->puntos_canjeados) : 0)); ?></span>
                </div>
                
                <?php if($canAfford): ?>
                    <div class="mt-2 flex items-center text-sm text-green-600">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Tienes suficientes puntos
                    </div>
                <?php else: ?>
                    <div class="mt-2 flex items-center text-sm text-red-600">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        Puntos insuficientes (necesitas <?php echo e(number_format($employee ? ($product->costo_puntos - ($employee->puntos_totales - $employee->puntos_canjeados)) : $product->costo_puntos)); ?> más)
                    </div>
                <?php endif; ?>
            </div>

            <!-- Redemption Form -->
            <?php if($product->activo && (!$product->stock_limitado || $product->stock_disponible > 0)): ?>
                <form id="redeem-form" class="space-y-4">
                    <?php echo csrf_field(); ?>
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
                        class="w-full btn-primary btn-lg <?php echo e(!$canAfford ? 'opacity-50 cursor-not-allowed' : ''); ?>"
                        <?php echo e(!$canAfford ? 'disabled' : ''); ?>>
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        Canjear por <?php echo e(number_format($product->costo_puntos)); ?> puntos
                    </button>
                </form>
            <?php else: ?>
                <div class="w-full bg-gray-100 text-gray-500 text-center py-4 rounded-lg">
                    <?php if(!$product->activo): ?>
                        Producto no disponible
                    <?php else: ?>
                        Sin stock disponible
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <div class="flex space-x-4">
                <button 
                    id="favorite-button-<?php echo e($product->id); ?>"
                    onclick="toggleFavorite(<?php echo e($product->id); ?>)"
                    class="btn-ghost flex-1"
                    data-product-id="<?php echo e($product->id); ?>"
                    data-is-favorite="<?php echo e($isFavorite ? 'true' : 'false'); ?>">
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2 <?php echo e($isFavorite ? 'text-red-500' : 'text-gray-400'); ?>" 
                             fill="<?php echo e($isFavorite ? 'currentColor' : 'none'); ?>" 
                             stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span id="favorite-text-<?php echo e($product->id); ?>">
                            <?php echo e($isFavorite ? 'Remover de favoritos' : 'Agregar a favoritos'); ?>

                        </span>
                    </div>
                </button>

                <a href="<?php echo e(route('products.index')); ?>" class="btn-secondary flex-1 text-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver al catálogo
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
<div id="message-container" class="fixed top-4 right-4 z-50 hidden">
    <div id="message" class="px-6 py-4 rounded-lg shadow-lg text-white"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const redeemForm = document.getElementById('redeem-form');
    const redeemButton = document.getElementById('redeem-button');
    
    // Load correct favorite status after authentication
    loadFavoriteStatus();
    
    // Listen for auth completion
    window.addEventListener('auth-completed', () => {
        console.log('Product details: Auth completed, loading favorite status');
        setTimeout(() => {
            loadFavoriteStatus();
        }, 100);
    });
    
    // Check if auth is already completed
    if (window.authInitialized && localStorage.getItem('firebase_token')) {
        setTimeout(() => {
            loadFavoriteStatus();
        }, 100);
    }
    
    if (redeemForm) {
        redeemForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (redeemButton.disabled) return;
            
            // Show loading state
            const originalText = redeemButton.innerHTML;
            redeemButton.disabled = true;
            redeemButton.innerHTML = '<div class="loading-spinner mr-2"></div>Procesando...';
            
            try {
                const formData = new FormData(redeemForm);
                
                const response = await fetch(`<?php echo e(route('products.redeem', $product)); ?>`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('firebase_token')}`,
                        'X-CSRF-TOKEN': formData.get('_token'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showMessage('¡Canje realizado exitosamente!', 'success');
                    // Redirect to orders after 2 seconds
                    setTimeout(() => {
                        window.location.href = '<?php echo e(route("orders.history")); ?>';
                    }, 2000);
                } else {
                    showMessage(data.message || 'Error al procesar el canje', 'error');
                    redeemButton.disabled = false;
                    redeemButton.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Redemption error:', error);
                showMessage('Error al procesar el canje', 'error');
                redeemButton.disabled = false;
                redeemButton.innerHTML = originalText;
            }
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

        const productId = <?php echo e($product->id); ?>;
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
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\sheets-backend\ugo-laravel\resources\views/products/show.blade.php ENDPATH**/ ?>