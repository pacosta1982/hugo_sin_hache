@extends('layouts.app')

@section('title', 'Pedido #' . $order->id)

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
                <a href="{{ route('orders.history') }}" class="hover:text-blue-600">Historial de Pedidos</a>
            </li>
            <li>
                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            </li>
            <li class="text-gray-900 font-medium">Pedido #{{ $order->id }}</li>
        </ol>
    </nav>

    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Pedido #{{ $order->id }}</h1>
                <p class="text-gray-600 mt-1">Realizado el {{ $order->created_at->format('d/m/Y \a \l\a\s H:i') }}</p>
            </div>
            <div class="text-right">
                <span class="status-badge status-{{ $order->estado }} text-lg px-4 py-2">
{{ $order->estado_display }}
                </span>
                <p class="text-2xl font-bold text-blue-600 mt-2">{{ number_format($order->puntos_utilizados) }} puntos</p>
            </div>
        </div>

        
        <div class="w-full bg-gray-200 rounded-full h-2">
            @php
                $progress = 0;
                switch($order->estado) {
                    case 'pending': $progress = 25; break;
                    case 'processing': $progress = 50; break;
                    case 'completed': $progress = 100; break;
                    case 'cancelled': $progress = 0; break;
                }
            @endphp
            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $progress }}%"></div>
        </div>

        
        <div class="flex justify-between mt-4 text-sm">
            <div class="flex flex-col items-center {{ in_array($order->estado, ['pending', 'processing', 'completed']) ? 'text-blue-600' : 'text-gray-400' }}">
                <div class="w-3 h-3 rounded-full {{ in_array($order->estado, ['pending', 'processing', 'completed']) ? 'bg-blue-600' : 'bg-gray-300' }} mb-1"></div>
                <span>Pedido creado</span>
            </div>
            <div class="flex flex-col items-center {{ in_array($order->estado, ['processing', 'completed']) ? 'text-blue-600' : 'text-gray-400' }}">
                <div class="w-3 h-3 rounded-full {{ in_array($order->estado, ['processing', 'completed']) ? 'bg-blue-600' : 'bg-gray-300' }} mb-1"></div>
                <span>En proceso</span>
            </div>
            <div class="flex flex-col items-center {{ $order->estado === 'completed' ? 'text-blue-600' : 'text-gray-400' }}">
                <div class="w-3 h-3 rounded-full {{ $order->estado === 'completed' ? 'bg-blue-600' : 'bg-gray-300' }} mb-1"></div>
                <span>Completado</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Detalles del Producto</h2>
                
                <div class="flex items-start space-x-6">
                    
                    <div class="flex-shrink-0">
                        <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                            @if($order->product && $order->product->imagen_url)
                                <img src="{{ $order->product->imagen_url }}" alt="{{ $order->product->nombre }}" class="w-full h-full object-cover rounded-lg">
                            @else
                                <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                </svg>
                            @endif
                        </div>
                    </div>

                    
                    <div class="flex-grow">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            {{ $order->product ? $order->product->nombre : 'Producto no disponible' }}
                        </h3>
                        
                        @if($order->product && $order->product->descripcion)
                            <p class="text-gray-600 mb-3">{{ $order->product->descripcion }}</p>
                        @endif

                        @if($order->product && $order->product->categoria)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $order->product->categoria }}
                            </span>
                        @endif

                        <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Costo:</span>
                                <span class="font-semibold text-blue-600">{{ number_format($order->puntos_utilizados) }} puntos</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Cantidad:</span>
                                <span class="font-semibold">1</span>
                            </div>
                        </div>
                    </div>
                </div>

                
                @if($order->observaciones)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-md font-semibold text-gray-900 mb-2">Observaciones</h4>
                        <p class="text-gray-700 bg-gray-50 rounded-lg p-3">{{ $order->observaciones }}</p>
                    </div>
                @endif

                
                @if($order->notas_admin)
                    <div class="mt-4">
                        <h4 class="text-md font-semibold text-gray-900 mb-2">Notas del Administrador</h4>
                        <p class="text-gray-700 bg-blue-50 rounded-lg p-3">{{ $order->notas_admin }}</p>
                    </div>
                @endif
            </div>
        </div>

        
        <div class="space-y-6">
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Información del Pedido</h2>
                
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-600">ID del Pedido:</dt>
                        <dd class="text-sm text-gray-900 font-mono">#{{ $order->id }}</dd>
                    </div>
                    
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-600">Fecha de creación:</dt>
                        <dd class="text-sm text-gray-900">{{ $order->created_at->format('d/m/Y') }}</dd>
                    </div>
                    
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-600">Hora:</dt>
                        <dd class="text-sm text-gray-900">{{ $order->created_at->format('H:i') }}</dd>
                    </div>
                    
                    @if($order->updated_at != $order->created_at)
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-600">Última actualización:</dt>
                            <dd class="text-sm text-gray-900">{{ $order->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    @endif
                    
                    <div class="flex justify-between pt-3 border-t border-gray-200">
                        <dt class="text-base font-semibold text-gray-900">Total de puntos:</dt>
                        <dd class="text-base font-bold text-blue-600">{{ number_format($order->puntos_utilizados) }}</dd>
                    </div>
                </dl>
            </div>

            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Información del Empleado</h2>
                
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Nombre:</dt>
                        <dd class="text-sm text-gray-900">{{ $employee ? $employee->nombre : '' }}</dd>
                    </div>
                    
                    @if($employee->email)
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Email:</dt>
                            <dd class="text-sm text-gray-900">{{ $employee ? $employee->email : '' }}</dd>
                        </div>
                    @endif
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Puntos disponibles:</dt>
                        <dd class="text-sm text-gray-900">{{ number_format($employee ? ($employee->puntos_totales - $employee->puntos_canjeados) : 0) }}</dd>
                    </div>
                </dl>
            </div>

            
            <div class="space-y-3">
                <a href="{{ route('orders.history') }}" class="w-full btn-secondary text-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver al historial
                </a>
                
                @if($order->estado === 'pending')
                    <button onclick="confirmCancel()" class="w-full btn-danger">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancelar pedido
                    </button>
                @endif
                
                <a href="{{ route('products.index') }}" class="w-full btn-primary text-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Explorar productos
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function confirmCancel() {
    if (confirm('¿Estás seguro de que quieres cancelar este pedido? Esta acción no se puede deshacer.')) {
        cancelOrder();
    }
}

async function cancelOrder() {
    try {
        const response = await fetch(`{{ route('orders.show', $order) }}/cancel`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('firebase_token')}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showMessage('Pedido cancelado exitosamente', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            showMessage(data.message || 'Error al cancelar el pedido', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showMessage('Error al cancelar el pedido', 'error');
    }
}

function showMessage(message, type) {
    if (window.showToast) {
        window.showToast(message, type);
    } else {
        alert(message);
    }
}
</script>
@endsection