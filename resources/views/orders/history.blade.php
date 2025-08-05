@extends('layouts.app')

@section('title', 'Historial de Pedidos')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Historial de Pedidos</h1>
        <p class="text-gray-600">Revisa todos tus canjes y el estado de cada pedido</p>
    </div>

    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="form-label">Estado</label>
                <select id="status-filter" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="pending">Pendiente</option>
                    <option value="processing">En proceso</option>
                    <option value="completed">Completado</option>
                    <option value="cancelled">Cancelado</option>
                </select>
            </div>
            <div>
                <label class="form-label">Desde</label>
                <input type="date" id="date-from" class="form-input">
            </div>
            <div>
                <label class="form-label">Hasta</label>
                <input type="date" id="date-to" class="form-input">
            </div>
            <div class="flex items-end">
                <button onclick="applyFilters()" class="btn-primary w-full">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filtrar
                </button>
            </div>
        </div>
    </div>

    
    <div id="orders-container" class="space-y-4">
        
        <div id="loading-state" class="text-center py-12">
            <div class="loading-spinner mx-auto mb-4"></div>
            <p class="text-gray-600">Cargando historial de pedidos...</p>
        </div>

        
        <div id="empty-state" class="hidden text-center py-12">
            <div class="max-w-md mx-auto">
                <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No hay pedidos</h3>
                <p class="text-gray-600 mb-6">Aún no has realizado ningún canje de productos.</p>
                <a href="{{ route('products.index') }}" class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Explorar Productos
                </a>
            </div>
        </div>

        
        <div id="orders-list"></div>
    </div>

    
    <div id="pagination-container" class="hidden mt-8">
        <nav class="flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
                <button id="prev-mobile" class="btn-secondary" disabled>Anterior</button>
                <button id="next-mobile" class="btn-secondary" disabled>Siguiente</button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Mostrando <span id="showing-from">1</span> a <span id="showing-to">10</span> de <span id="total-orders">0</span> pedidos
                    </p>
                </div>
                <div id="pagination-links">
                    
                </div>
            </div>
        </nav>
    </div>
</div>


<div id="order-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            
            <div class="flex items-center justify-between pb-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900" id="modal-title">Detalles del Pedido</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            
            <div id="modal-body" class="py-4">
                
            </div>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let currentFilters = {};

document.addEventListener('DOMContentLoaded', function() {
    loadOrders();
});

async function loadOrders(page = 1, filters = {}) {
    const loadingState = document.getElementById('loading-state');
    const emptyState = document.getElementById('empty-state');
    const ordersList = document.getElementById('orders-list');
    const paginationContainer = document.getElementById('pagination-container');
    
    // Show loading state
    loadingState.classList.remove('hidden');
    emptyState.classList.add('hidden');
    ordersList.innerHTML = '';
    paginationContainer.classList.add('hidden');
    
    try {
        const params = new URLSearchParams({
            page: page,
            ...filters
        });
        
        const response = await fetch(`/api/pedidos?${params}`, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('firebase_token')}`,
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to load orders');
        }
        
        const data = await response.json();
        
        loadingState.classList.add('hidden');
        
        if (data.orders && data.orders.length > 0) {
            renderOrders(data.orders);
            renderPagination(data.pagination);
            paginationContainer.classList.remove('hidden');
        } else {
            emptyState.classList.remove('hidden');
        }
        
    } catch (error) {
        console.error('Error loading orders:', error);
        loadingState.classList.add('hidden');
        showMessage('Error al cargar el historial de pedidos', 'error');
    }
}

function renderOrders(orders) {
    const ordersList = document.getElementById('orders-list');
    
    ordersList.innerHTML = orders.map(order => `
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow cursor-pointer" 
             onclick="showOrderDetails(${order.id})">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">#${order.id}</h3>
                    <p class="text-sm text-gray-600">${formatDate(order.created_at)}</p>
                </div>
                <div class="text-right">
                    <span class="status-badge status-${order.estado}">${getStatusText(order.estado)}</span>
                    <p class="text-lg font-bold text-blue-600 mt-1">${order.puntos_utilizados} puntos</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                        ${order.product_image ? 
                            `<img src="${order.product_image}" alt="${order.product_name}" class="w-full h-full object-cover rounded-lg">` :
                            `<svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                            </svg>`
                        }
                    </div>
                </div>
                <div class="flex-grow">
                    <h4 class="font-medium text-gray-900">${order.product_name}</h4>
                    ${order.observaciones ? `<p class="text-sm text-gray-600 mt-1">${order.observaciones}</p>` : ''}
                </div>
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </div>
        </div>
    `).join('');
}

function renderPagination(pagination) {
    const showingFrom = document.getElementById('showing-from');
    const showingTo = document.getElementById('showing-to');
    const totalOrders = document.getElementById('total-orders');
    
    showingFrom.textContent = pagination.from || 0;
    showingTo.textContent = pagination.to || 0;
    totalOrders.textContent = pagination.total || 0;
    
    // Update pagination buttons (simplified version)
    const prevMobile = document.getElementById('prev-mobile');
    const nextMobile = document.getElementById('next-mobile');
    
    prevMobile.disabled = !pagination.prev_page_url;
    nextMobile.disabled = !pagination.next_page_url;
    
    if (pagination.prev_page_url) {
        prevMobile.onclick = () => loadOrders(pagination.current_page - 1, currentFilters);
    }
    
    if (pagination.next_page_url) {
        nextMobile.onclick = () => loadOrders(pagination.current_page + 1, currentFilters);
    }
}

function applyFilters() {
    const status = document.getElementById('status-filter').value;
    const dateFrom = document.getElementById('date-from').value;
    const dateTo = document.getElementById('date-to').value;
    
    currentFilters = {
        ...(status && { status }),
        ...(dateFrom && { date_from: dateFrom }),
        ...(dateTo && { date_to: dateTo })
    };
    
    currentPage = 1;
    loadOrders(currentPage, currentFilters);
}

async function showOrderDetails(orderId) {
    const modal = document.getElementById('order-modal');
    const modalBody = document.getElementById('modal-body');
    
    modalBody.innerHTML = '<div class="text-center py-8"><div class="loading-spinner mx-auto"></div></div>';
    modal.classList.remove('hidden');
    
    try {
        const response = await fetch(`/api/pedidos/${orderId}`, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('firebase_token')}`,
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to load order details');
        }
        
        const order = await response.json();
        
        modalBody.innerHTML = `
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Información del Pedido</h4>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-600">ID del Pedido</dt>
                                <dd class="text-sm text-gray-900">#${order.id}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-600">Fecha</dt>
                                <dd class="text-sm text-gray-900">${formatDate(order.created_at)}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-600">Estado</dt>
                                <dd><span class="status-badge status-${order.estado}">${getStatusText(order.estado)}</span></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-600">Puntos Utilizados</dt>
                                <dd class="text-sm text-blue-600 font-semibold">${order.puntos_utilizados}</dd>
                            </div>
                        </dl>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Producto</h4>
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                ${order.product_image ? 
                                    `<img src="${order.product_image}" alt="${order.product_name}" class="w-full h-full object-cover rounded-lg">` :
                                    `<svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                    </svg>`
                                }
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-900">${order.product_name}</h5>
                                <p class="text-sm text-gray-600">${order.puntos_utilizados} puntos</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                ${order.observaciones ? `
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Observaciones</h4>
                        <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">${order.observaciones}</p>
                    </div>
                ` : ''}
                
                ${order.notas_admin ? `
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Notas del Administrador</h4>
                        <p class="text-sm text-gray-700 bg-blue-50 rounded-lg p-3">${order.notas_admin}</p>
                    </div>
                ` : ''}
            </div>
        `;
        
    } catch (error) {
        console.error('Error loading order details:', error);
        modalBody.innerHTML = '<div class="text-center py-8 text-red-600">Error al cargar los detalles del pedido</div>';
    }
}

function closeModal() {
    document.getElementById('order-modal').classList.add('hidden');
}

function getStatusText(status) {
    const statusMap = {
        'pending': 'Pendiente',
        'processing': 'En proceso',
        'completed': 'Completado',
        'cancelled': 'Cancelado'
    };
    return statusMap[status] || status;
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function showMessage(message, type) {
    // Reuse the toast function from app.js if available
    if (window.showToast) {
        window.showToast(message, type);
    } else {
        alert(message);
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('order-modal');
    if (e.target === modal) {
        closeModal();
    }
});
</script>
@endsection