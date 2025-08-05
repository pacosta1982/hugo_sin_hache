<div>
    
    <div id="dashboard-loading" class="text-center py-12">
        <div class="loading-spinner mx-auto mb-4"></div>
        <p class="text-gray-600">Cargando tu dashboard...</p>
    </div>

    
    <div id="dashboard-content" class="hidden">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                ¡Hola, <span id="employee-name">{{ $employee ? $employee->nombre : '' }}</span>!
            </h1>
            <p class="mt-2 text-gray-600">
                Bienvenido a tu dashboard de puntos UGo
            </p>
        </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-teal-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Puntos Disponibles</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_points'] ?? 0) }}</p>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Puntos Canjeados</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['redeemed_points'] ?? 0) }}</p>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V7l-7-5z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Pedidos</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_orders'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pedidos Pendientes</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_orders'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Actividad Reciente</h3>
                        <a href="{{ route('orders.history') }}" class="text-sm text-teal-600 hover:text-teal-700">
                            Ver todo
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($recentOrders->count() > 0)
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                @foreach($recentOrders as $index => $order)
                                    <li>
                                        <div class="relative pb-8">
                                            @if(!$loop->last)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                                        {{ $order->estado === 'pending' ? 'bg-yellow-500' : '' }}
                                                        {{ $order->estado === 'processing' ? 'bg-teal-500' : '' }}
                                                        {{ $order->estado === 'completed' ? 'bg-green-500' : '' }}
                                                        {{ $order->estado === 'cancelled' ? 'bg-red-500' : '' }}">
                                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500">
                                                            Canjeaste <span class="font-medium text-gray-900">{{ $order->producto_nombre }}</span>
                                                        </p>
                                                        <p class="text-xs text-gray-400">
                                                            {{ $order->puntos_utilizados }} puntos utilizados
                                                        </p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        <time datetime="{{ $order->fecha->format('Y-m-d') }}">
                                                            {{ $order->fecha->diffForHumans() }}
                                                        </time>
                                                        <div class="mt-1">
                                                            <span class="status-badge 
                                                                {{ $order->estado === 'pending' ? 'status-pending' : '' }}
                                                                {{ $order->estado === 'processing' ? 'status-processing' : '' }}
                                                                {{ $order->estado === 'completed' ? 'status-completed' : '' }}
                                                                {{ $order->estado === 'cancelled' ? 'status-cancelled' : '' }}">
                                                                {{ $order->estado_display }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Sin actividad reciente</h3>
                            <p class="mt-1 text-sm text-gray-500">¡Comienza canjeando tu primer producto!</p>
                            <div class="mt-6">
                                <a href="{{ route('products.index') }}" class="btn-primary">
                                    Ver Productos
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        
        <div>
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Productos Destacados</h3>
                        <a href="{{ route('products.index') }}" class="text-sm text-teal-600 hover:text-teal-700">
                            Ver todos
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($featuredProducts->count() > 0)
                        <div class="space-y-4">
                            @foreach($featuredProducts->take(4) as $product)
                                <div class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors"
                                     wire:click="redirectToProduct({{ $product->id }})">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5zM10 12a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $product->nombre }}
                                        </p>
                                        <p class="text-xs text-gray-500 truncate">
                                            {{ $product->categoria }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0 text-right">
                                        <p class="text-sm font-bold text-teal-600">
                                            {{ number_format($product->costo_puntos) }} pts
                                        </p>
                                        @if($product->stock !== -1)
                                            <p class="text-xs text-gray-400">
                                                Stock: {{ $product->stock }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($featuredProducts->count() > 4)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <a href="{{ route('products.index') }}" class="w-full btn-secondary text-center">
                                    Ver {{ $featuredProducts->count() - 4 }} productos más
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-6">
                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5z" clip-rule="evenodd"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No hay productos disponibles</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div> 

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check authentication and load dashboard data
            setTimeout(function() {
                loadDashboard();
            }, 1000);
        });

        async function loadDashboard() {
            try {
                console.log('🎯 Starting dashboard load...');
                
                // Wait for Firebase auth to initialize
                console.log('⏳ Waiting for auth initialization...');
                const user = await waitForAuth();
                
                if (!user) {
                    console.log('❌ No authenticated user found after waiting');
                    throw new Error('No authenticated user');
                }

                console.log('✅ Auth user found:', user.email);

                // Get current user data from API
                console.log('📡 Calling /api/me...');
                const response = await apiRequest('/api/me');
                console.log('📨 API response:', response);
                
                if (response && response.employee) {
                    console.log('👤 Employee data received:', response.employee);
                    
                    // Update employee name
                    document.getElementById('employee-name').textContent = response.employee.nombre;
                    
                    // Call Livewire method to load dashboard data
                    if (typeof Livewire !== 'undefined') {
                        console.log('🔧 Calling Livewire loadDashboardData...');
                        await Livewire.find('{{ $_instance->getId() }}').call('loadDashboardData', response.employee);
                        console.log('✅ Livewire call completed');
                        
                        // Small delay to allow Livewire to re-render
                        await new Promise(resolve => setTimeout(resolve, 500));
                        console.log('⏱️ Delay completed, checking DOM state...');
                    } else {
                        console.log('❌ Livewire not found');
                    }
                    
                    // Hide loading, show content
                    const loadingEl = document.getElementById('dashboard-loading');
                    const contentEl = document.getElementById('dashboard-content');
                    
                    console.log('🔍 DOM elements found:');
                    console.log('- Loading element:', loadingEl);
                    console.log('- Content element:', contentEl);
                    
                    if (loadingEl) {
                        loadingEl.classList.add('hidden');
                        console.log('✅ Loading hidden');
                    } else {
                        console.log('❌ Loading element not found');
                    }
                    
                    if (contentEl) {
                        contentEl.classList.remove('hidden');
                        console.log('✅ Content shown');
                        console.log('- Content element classes:', contentEl.className);
                    } else {
                        console.log('❌ Content element not found');
                    }
                    
                    console.log('✅ Dashboard content displayed');
                } else {
                    console.log('❌ No employee data in response');
                    throw new Error('Failed to get user data');
                }
            } catch (error) {
                console.error('❌ Dashboard loading failed:', error);
                console.error('Error details:', error.message);
                // Redirect to login
                window.location.href = '/login';
            }
        }
    </script>
</div>