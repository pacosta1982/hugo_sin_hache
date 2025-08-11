@extends('layouts.app')

@section('title', 'Historial de Pedidos')

@section('content')
<div class="container mx-auto px-4 py-8 space-y-8">
    
    
    <div class="relative">
        <div class="bg-gradient-to-r from-blue-50/80 via-indigo-50/80 to-blue-50/60 rounded-3xl p-8 border border-blue-100">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between">
                <div class="flex items-center space-x-6 mb-6 lg:mb-0">
                    
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-3xl flex items-center justify-center shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    
                    
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">
                            Historial de Pedidos
                        </h1>
                        <p class="text-lg text-gray-600 mb-3">
                            Tu cronología completa de canjes y recompensas
                        </p>
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span id="total-points-used">0</span> puntos canjeados
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd"/>
                                </svg>
                                <span id="total-orders-count">0</span> pedidos realizados
                            </span>
                        </div>
                    </div>
                </div>

                
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4" id="status-preview">
                    <div class="text-center p-3 bg-white/50 rounded-2xl border border-white/20">
                        <p class="text-2xl font-bold text-yellow-600" id="pending-count">0</p>
                        <p class="text-xs text-gray-600">Pendientes</p>
                    </div>
                    <div class="text-center p-3 bg-white/50 rounded-2xl border border-white/20">
                        <p class="text-2xl font-bold text-blue-600" id="processing-count">0</p>
                        <p class="text-xs text-gray-600">En proceso</p>
                    </div>
                    <div class="text-center p-3 bg-white/50 rounded-2xl border border-white/20">
                        <p class="text-2xl font-bold text-green-600" id="completed-count">0</p>
                        <p class="text-xs text-gray-600">Completados</p>
                    </div>
                    <div class="text-center p-3 bg-white/50 rounded-2xl border border-white/20">
                        <p class="text-2xl font-bold text-red-600" id="cancelled-count">0</p>
                        <p class="text-xs text-gray-600">Cancelados</p>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="absolute -top-4 -right-4 w-32 h-32 bg-blue-200/30 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-indigo-200/30 rounded-full blur-3xl"></div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-gray-600 to-gray-800 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Filtros y Búsqueda</h3>
                    <p class="text-sm text-gray-500">Encuentra pedidos específicos</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <div class="form-group">
                    <label class="form-label flex items-center">
                        <svg class="w-4 h-4 mr-2 text-itti-primary" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Estado
                    </label>
                    <select id="status-filter" class="form-input">
                        <option value="">🗂️ Todos los estados</option>
                        <option value="pending">⏳ Pendiente</option>
                        <option value="processing">🔄 En proceso</option>
                        <option value="completed">✅ Completado</option>
                        <option value="cancelled">❌ Cancelado</option>
                    </select>
                </div>

                
                <div class="form-group">
                    <label class="form-label flex items-center">
                        <svg class="w-4 h-4 mr-2 text-itti-primary" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        Desde
                    </label>
                    <input type="date" id="date-from" class="form-input">
                </div>

                
                <div class="form-group">
                    <label class="form-label flex items-center">
                        <svg class="w-4 h-4 mr-2 text-itti-primary" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        Hasta
                    </label>
                    <input type="date" id="date-to" class="form-input">
                </div>

                
                <div class="form-group">
                    <label class="form-label">&nbsp;</label>
                    <div class="flex space-x-2">
                        <button onclick="applyFilters()" class="btn-primary flex-1 group">
                            <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Filtrar
                        </button>
                        <button onclick="clearFilters()" class="btn-secondary group">
                            <svg class="w-4 h-4 group-hover:rotate-90 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div id="orders-container" class="space-y-4">
        
        <div id="loading-state" class="text-center py-20">
            <div class="mx-auto w-20 h-20 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center mb-6">
                <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-200 border-t-blue-500"></div>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Cargando tu historial</h3>
            <p class="text-gray-600">Obteniendo tus pedidos y canjes...</p>
        </div>

        
        <div id="empty-state" class="hidden text-center py-20">
            <div class="mx-auto w-32 h-32 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center mb-8 relative">
                <svg class="w-16 h-16 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                
                <div class="absolute -top-2 -right-2 w-6 h-6 bg-indigo-300 rounded-full animate-bounce" style="animation-delay: 0.5s;"></div>
                <div class="absolute -bottom-2 -left-2 w-4 h-4 bg-blue-300 rounded-full animate-bounce" style="animation-delay: 1s;"></div>
            </div>
            
            <div class="max-w-md mx-auto">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">
                    ¡Tu aventura de canjes comenzará pronto!
                </h3>
                <p class="text-lg text-gray-600 mb-8">
                    Aún no has realizado ningún canje. Explora nuestro catálogo y 
                    <span class="font-semibold text-blue-500">¡convierte tus puntos en increíbles recompensas!</span>
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('products.index') }}" class="btn-primary btn-lg group">
                        <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        Explorar Catálogo
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn-secondary btn-lg group">
                        <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        Ir al Dashboard
                    </a>
                </div>
            </div>
            
            
            <div class="mt-16 p-6 bg-gradient-to-r from-gray-50 to-slate-50 rounded-2xl border border-gray-200 max-w-2xl mx-auto">
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    ¿Cómo funciona el sistema?
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                    <div class="flex items-start space-x-2">
                        <div class="w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center mt-0.5 flex-shrink-0">
                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        </div>
                        <p>Acumula puntos completando tareas y alcanzando objetivos</p>
                    </div>
                    <div class="flex items-start space-x-2">
                        <div class="w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center mt-0.5 flex-shrink-0">
                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        </div>
                        <p>Explora el catálogo y encuentra productos que te gusten</p>
                    </div>
                    <div class="flex items-start space-x-2">
                        <div class="w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center mt-0.5 flex-shrink-0">
                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        </div>
                        <p>Canjea tus puntos por productos y experiencias increíbles</p>
                    </div>
                    <div class="flex items-start space-x-2">
                        <div class="w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center mt-0.5 flex-shrink-0">
                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        </div>
                        <p>Rastrea el estado de tus pedidos en este historial</p>
                    </div>
                </div>
            </div>
        </div>

        
        <div id="orders-list" class="relative">
            
        </div>
    </div>

    
    <div id="pagination-container" class="hidden mt-12">
        <div class="card">
            <div class="card-body">
                <nav class="flex items-center justify-between">
                    
                    <div class="flex-1 flex justify-between sm:hidden">
                        <button id="prev-mobile" class="btn-secondary" disabled>
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Anterior
                        </button>
                        <button id="next-mobile" class="btn-secondary" disabled>
                            Siguiente
                            <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                    
                    
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center">
                                <span class="text-sm font-bold text-white" id="total-orders">0</span>
                            </div>
                            <p class="text-sm text-gray-700">
                                Mostrando <span class="font-semibold" id="showing-from">1</span> - <span class="font-semibold" id="showing-to">10</span> de <span class="font-semibold" id="total-orders-text">0</span> pedidos
                            </p>
                        </div>
                        <div id="pagination-links" class="flex items-center space-x-2">
                            
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>



<div id="order-modal" class="hidden fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="relative w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="card shadow-2xl border-0">
                <div class="card-header">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900" id="modal-title">Detalles del Pedido</h3>
                                <p class="text-sm text-gray-500">Información completa del canje</p>
                            </div>
                        </div>
                        <button onclick="closeModal()" class="w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-xl flex items-center justify-center transition-colors group">
                            <svg class="w-5 h-5 text-gray-600 group-hover:text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div id="modal-body" class="card-body">
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let currentFilters = {};
let allOrdersData = [];

document.addEventListener('DOMContentLoaded', function() {
    loadOrders();
    initializeAnimations();
});

async function loadOrders(page = 1, filters = {}) {
    const loadingState = document.getElementById('loading-state');
    const emptyState = document.getElementById('empty-state');
    const ordersList = document.getElementById('orders-list');
    const paginationContainer = document.getElementById('pagination-container');
    
    // Show loading state with fade effect
    showElement(loadingState);
    hideElement(emptyState);
    ordersList.innerHTML = '';
    hideElement(paginationContainer);
    
    try {
        const params = new URLSearchParams({
            page: page,
            ...filters
        });
        
        console.log('Loading orders with params:', params.toString());
        
        const token = localStorage.getItem('firebase_token');
        if (!token) {
            window.location.href = '/login';
            return;
        }
        
        const response = await fetch(`/api/pedidos?${params}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to load orders');
        }
        
        const data = await response.json();
        allOrdersData = data.orders || [];
        
        hideElement(loadingState);
        
        if (data.success && data.orders && data.orders.length > 0) {
            renderTimelineOrders(data.orders);
            renderPagination(data.pagination);
            updateHeaderStats(data.orders);
            showElement(paginationContainer);
        } else {
            // Check if filters are applied and show appropriate message
            const hasFilters = Object.keys(filters).length > 0;
            const emptyMessage = hasFilters ? 
                'No se encontraron pedidos que coincidan con los filtros aplicados.' : 
                'Aún no has realizado ningún pedido.';
            
            // Update empty state message if needed
            const emptyStateText = document.querySelector('#empty-state h3');
            if (emptyStateText && hasFilters) {
                emptyStateText.textContent = 'No hay resultados';
                const emptyStateDesc = document.querySelector('#empty-state p');
                if (emptyStateDesc) {
                    emptyStateDesc.innerHTML = emptyMessage + ' <br><span class="font-semibold text-blue-500">Intenta cambiar los filtros o limpiarlos.</span>';
                }
            }
            
            showElement(emptyState);
        }
        
    } catch (error) {
        console.error('Error loading orders:', error);
        hideElement(loadingState);
        showNotification('Error al cargar el historial de pedidos', 'error');
    }
}

// Enhanced Timeline Rendering
function renderTimelineOrders(orders) {
    const ordersList = document.getElementById('orders-list');
    
    // Create timeline container with background line
    const timelineHTML = `
        <div class="timeline-container relative">
            
            <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-gradient-to-b from-blue-200 via-indigo-300 to-blue-200"></div>
            
            
            <div class="timeline-items space-y-8">
                ${orders.map((order, index) => createTimelineItem(order, index)).join('')}
            </div>
        </div>
    `;
    
    ordersList.innerHTML = timelineHTML;
    
    // Add staggered animations
    setTimeout(() => {
        const items = ordersList.querySelectorAll('.timeline-item');
        items.forEach((item, index) => {
            setTimeout(() => {
                item.classList.add('timeline-item-visible');
            }, index * 150);
        });
    }, 100);
}

function createTimelineItem(order, index) {
    const statusColors = {
        'pending': 'bg-yellow-500',
        'processing': 'bg-blue-500',
        'completed': 'bg-green-500',
        'cancelled': 'bg-red-500'
    };
    
    const statusIcons = {
        'pending': `<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>`,
        'processing': `<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/></svg>`,
        'completed': `<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>`,
        'cancelled': `<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>`
    };

    return `
        <div class="timeline-item relative opacity-0 transform translate-y-8 transition-all duration-700 ease-out">
            
            <div class="absolute left-6 z-10">
                <div class="w-8 h-8 ${statusColors[order.estado]} rounded-full border-4 border-white shadow-lg flex items-center justify-center text-white">
                    ${statusIcons[order.estado]}
                </div>
            </div>
            
            
            <div class="ml-20 pb-8">
                <div class="card hover:shadow-xl transition-all duration-300 cursor-pointer group" 
                     onclick="showOrderDetails(${order.id})">
                    <div class="card-body">
                        
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 group-hover:text-itti-primary transition-colors">
                                    Pedido #${order.id}
                                </h3>
                                <p class="text-sm text-gray-500">${formatDate(order.created_at)}</p>
                            </div>
                            <div class="text-right">
                                <span class="status-badge status-${order.estado} mb-2">${getStatusText(order.estado)}</span>
                                <p class="text-2xl font-bold text-itti-primary">${order.puntos_utilizados} puntos</p>
                            </div>
                        </div>
                        
                        
                        <div class="flex items-center space-x-6">
                            <div class="flex-shrink-0">
                                <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center group-hover:from-itti-primary/10 group-hover:to-itti-secondary/10 transition-all duration-300">
                                    ${(order.product && order.product.imagen_url) ? 
                                        `<img src="${order.product.imagen_url}" alt="${order.producto_nombre}" class="w-full h-full object-cover rounded-2xl">` :
                                        `<svg class="w-10 h-10 text-gray-400 group-hover:text-itti-primary transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                        </svg>`
                                    }
                                </div>
                            </div>
                            <div class="flex-grow">
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">${order.producto_nombre}</h4>
                                ${order.observaciones ? `<p class="text-sm text-gray-600 bg-gray-50 rounded-lg px-3 py-2">${order.observaciones}</p>` : ''}
                                <div class="flex items-center mt-3 space-x-4 text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                        </svg>
                                        ${formatRelativeDate(order.created_at)}
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        ${order.puntos_utilizados} pts utilizados
                                    </span>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-gray-400 group-hover:text-itti-primary group-hover:translate-x-1 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Enhanced pagination with smooth animations
function renderPagination(pagination) {
    const showingFrom = document.getElementById('showing-from');
    const showingTo = document.getElementById('showing-to');
    const totalOrders = document.getElementById('total-orders');
    const totalOrdersText = document.getElementById('total-orders-text');
    
    // Update pagination info with animations
    animateCountUp(showingFrom, pagination.from || 0);
    animateCountUp(showingTo, pagination.to || 0);
    animateCountUp(totalOrders, pagination.total || 0);
    animateCountUp(totalOrdersText, pagination.total || 0);
    
    // Update pagination buttons
    const prevMobile = document.getElementById('prev-mobile');
    const nextMobile = document.getElementById('next-mobile');
    const paginationLinks = document.getElementById('pagination-links');
    
    prevMobile.disabled = !pagination.prev_page_url;
    nextMobile.disabled = !pagination.next_page_url;
    
    if (pagination.prev_page_url) {
        prevMobile.onclick = () => loadOrders(pagination.current_page - 1, currentFilters);
    }
    
    if (pagination.next_page_url) {
        nextMobile.onclick = () => loadOrders(pagination.current_page + 1, currentFilters);
    }
    
    // Enhanced desktop pagination
    let paginationHTML = '';
    
    // Previous button
    if (pagination.prev_page_url) {
        paginationHTML += `<button onclick="loadOrders(${pagination.current_page - 1}, currentFilters)" class="btn-ghost btn-sm">Anterior</button>`;
    }
    
    // Page numbers (simplified)
    for (let i = Math.max(1, pagination.current_page - 2); i <= Math.min(pagination.last_page, pagination.current_page + 2); i++) {
        if (i === pagination.current_page) {
            paginationHTML += `<span class="btn-primary btn-sm">${i}</span>`;
        } else {
            paginationHTML += `<button onclick="loadOrders(${i}, currentFilters)" class="btn-ghost btn-sm">${i}</button>`;
        }
    }
    
    // Next button
    if (pagination.next_page_url) {
        paginationHTML += `<button onclick="loadOrders(${pagination.current_page + 1}, currentFilters)" class="btn-ghost btn-sm">Siguiente</button>`;
    }
    
    paginationLinks.innerHTML = paginationHTML;
}

// Enhanced filters with smooth UX
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
    
    // Show loading animation
    if (window.showToast) {
        window.showToast('Aplicando filtros...', 'info');
    }
    
    console.log('Applying filters:', currentFilters);
    loadOrders(currentPage, currentFilters);
}

function clearFilters() {
    document.getElementById('status-filter').value = '';
    document.getElementById('date-from').value = '';
    document.getElementById('date-to').value = '';
    
    currentFilters = {};
    currentPage = 1;
    
    if (window.showToast) {
        window.showToast('Filtros limpiados', 'success');
    }
    
    loadOrders(currentPage, currentFilters);
}

// Update header statistics
function updateHeaderStats(orders) {
    const totalPointsUsed = document.getElementById('total-points-used');
    const totalOrdersCount = document.getElementById('total-orders-count');
    const statusCounts = {
        pending: document.getElementById('pending-count'),
        processing: document.getElementById('processing-count'),
        completed: document.getElementById('completed-count'),
        cancelled: document.getElementById('cancelled-count')
    };
    
    // Calculate totals
    const pointsUsed = orders.reduce((sum, order) => sum + (order.puntos_utilizados || 0), 0);
    const statusBreakdown = orders.reduce((counts, order) => {
        counts[order.estado] = (counts[order.estado] || 0) + 1;
        return counts;
    }, {});
    
    // Update with animations
    animateCountUp(totalPointsUsed, pointsUsed);
    animateCountUp(totalOrdersCount, orders.length);
    
    Object.keys(statusCounts).forEach(status => {
        if (statusCounts[status]) {
            animateCountUp(statusCounts[status], statusBreakdown[status] || 0);
        }
    });
}

// Utility functions
function animateCountUp(element, target) {
    const start = parseInt(element.textContent) || 0;
    const duration = 1000;
    const startTime = performance.now();
    
    function updateCount(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const current = Math.floor(start + (target - start) * progress);
        
        element.textContent = current.toLocaleString();
        
        if (progress < 1) {
            requestAnimationFrame(updateCount);
        }
    }
    
    requestAnimationFrame(updateCount);
}

function showElement(element, animationClass = 'fade-in') {
    element.classList.remove('hidden');
    element.classList.add(animationClass);
}

function hideElement(element) {
    element.classList.add('hidden');
    element.classList.remove('fade-in');
}

async function showOrderDetails(orderId) {
    const modal = document.getElementById('order-modal');
    const modalBody = document.getElementById('modal-body');
    
    modalBody.innerHTML = '<div class="text-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-4 border-blue-200 border-t-blue-500 mx-auto"></div><p class="mt-2 text-gray-600">Cargando detalles...</p></div>';
    modal.classList.remove('hidden');
    
    try {
        const token = localStorage.getItem('firebase_token');
        if (!token) {
            window.location.href = '/login';
            return;
        }
        
        const response = await fetch(`/api/pedidos/${orderId}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to load order details');
        }
        
        const data = await response.json();
        
        if (data.success) {
            const order = data;
            const statusColor = getStatusColor(order.estado);
            const statusText = getStatusText(order.estado);
            
            modalBody.innerHTML = `
                <div class="space-y-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        
                        <div class="card p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" clip-rule="evenodd"/>
                                </svg>
                                Información del Producto
                            </h3>
                            
                            ${order.product_image ? `
                                <img src="${order.product_image}" alt="${order.product_name}" class="w-full h-48 object-cover rounded-xl mb-4">
                            ` : ''}
                            
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Producto:</span>
                                    <span class="font-medium">${order.product_name}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Puntos utilizados:</span>
                                    <span class="font-medium text-yellow-600">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                                        </svg>
                                        ${order.puntos_utilizados}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Fecha:</span>
                                    <span class="font-medium">${formatDate(order.created_at)}</span>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="space-y-6">
                            <div class="card p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    Estado del Pedido
                                </h3>
                                
                                <div class="text-center">
                                    <div class="w-20 h-20 ${statusColor.bg} border-4 ${statusColor.border} rounded-full flex items-center justify-center mx-auto mb-4">
                                        ${getStatusSVG(order.estado)}
                                    </div>
                                    <p class="text-xl font-bold text-gray-900 mb-2">${statusText}</p>
                                    <p class="text-gray-600 text-sm">${getStatusDescription(order.estado)}</p>
                                </div>
                            </div>
                            
                            ${order.estado === 'pending' ? `
                                <div class="card p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-3 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                                        </svg>
                                        Acciones
                                    </h3>
                                    <button onclick="cancelOrder(${order.id})" class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-4 rounded-xl transition-colors duration-200 flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Cancelar Pedido
                                    </button>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                    
                    ${order.observaciones ? `
                        <div class="card p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-3 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                Observaciones
                            </h3>
                            <p class="text-gray-700 italic bg-gray-50 p-4 rounded-xl">${order.observaciones}</p>
                        </div>
                    ` : ''}
                    
                    ${order.notas_admin ? `
                        <div class="card p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-3 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                                </svg>
                                Notas del Administrador
                            </h3>
                            <p class="text-gray-700 bg-purple-50 p-4 rounded-xl border border-purple-200">${order.notas_admin}</p>
                        </div>
                    ` : ''}
                </div>
            `;
        } else {
            modalBody.innerHTML = '<div class="text-center py-8 text-red-600">Error al cargar los detalles del pedido</div>';
        }
        
    } catch (error) {
        console.error('Error loading order details:', error);
        modalBody.innerHTML = '<div class="text-center py-8 text-red-600">Error al cargar los detalles del pedido</div>';
    }
}

// Cancel order function
async function cancelOrder(orderId) {
    if (!confirm('¿Estás seguro de que quieres cancelar este pedido?')) {
        return;
    }
    
    try {
        const token = localStorage.getItem('firebase_token');
        if (!token) {
            window.location.href = '/login';
            return;
        }
        
        const response = await fetch(`/api/pedidos/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('Pedido cancelado exitosamente', 'success');
            closeModal();
            loadOrders(currentPage, currentFilters); // Reload current page
        } else {
            showNotification(data.message || 'Error al cancelar el pedido', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Error al cancelar el pedido', 'error');
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

function formatRelativeDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffInMs = now - date;
    const diffInDays = Math.floor(diffInMs / (1000 * 60 * 60 * 24));
    
    if (diffInDays === 0) {
        return 'Hoy';
    } else if (diffInDays === 1) {
        return 'Ayer';
    } else if (diffInDays < 7) {
        return `Hace ${diffInDays} días`;
    } else if (diffInDays < 30) {
        const weeks = Math.floor(diffInDays / 7);
        return `Hace ${weeks} semana${weeks > 1 ? 's' : ''}`;
    } else if (diffInDays < 365) {
        const months = Math.floor(diffInDays / 30);
        return `Hace ${months} mes${months > 1 ? 'es' : ''}`;
    } else {
        const years = Math.floor(diffInDays / 365);
        return `Hace ${years} año${years > 1 ? 's' : ''}`;
    }
}

function getStatusColor(status) {
    const colors = {
        'pending': {
            bg: 'bg-yellow-500',
            border: 'border-yellow-400',
            text: 'text-yellow-800'
        },
        'processing': {
            bg: 'bg-blue-500',
            border: 'border-blue-400',
            text: 'text-blue-800'
        },
        'completed': {
            bg: 'bg-green-500',
            border: 'border-green-400',
            text: 'text-green-800'
        },
        'cancelled': {
            bg: 'bg-red-500',
            border: 'border-red-400',
            text: 'text-red-800'
        }
    };
    return colors[status] || colors.pending;
}

function getStatusSVG(status) {
    const svgs = {
        'pending': `<svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
        </svg>`,
        'processing': `<svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
        </svg>`,
        'completed': `<svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>`,
        'cancelled': `<svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>`
    };
    return svgs[status] || svgs.pending;
}

function getStatusDescription(status) {
    const descriptions = {
        'pending': 'Tu pedido está pendiente de procesamiento',
        'processing': 'Estamos preparando tu pedido',
        'completed': 'Tu pedido ha sido completado exitosamente',
        'cancelled': 'Este pedido ha sido cancelado'
    };
    return descriptions[status] || '';
}

// Initialize animations
function initializeAnimations() {
    // Add CSS for timeline animations
    const style = document.createElement('style');
    style.textContent = `
        .timeline-item-visible {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }
        
        .fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }
        
        .fade-out {
            animation: fadeOut 0.3s ease-out forwards;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        
        @keyframes fadeOut {
            from { opacity: 1; transform: scale(1); }
            to { opacity: 0; transform: scale(0.95); }
        }
        
        .timeline-container::before {
            content: '';
            position: absolute;
            left: 1.75rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, transparent, #e2e8f0, #cbd5e1, #e2e8f0, transparent);
            border-radius: 1px;
        }
    `;
    document.head.appendChild(style);
}

// Enhanced notification system
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 max-w-sm w-full bg-white rounded-2xl shadow-2xl border-l-4 transform translate-x-full transition-all duration-300 ease-out`;
    
    const colors = {
        success: 'border-green-500',
        error: 'border-red-500',
        warning: 'border-yellow-500',
        info: 'border-blue-500'
    };
    
    const icons = {
        success: `<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>`,
        error: `<svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 000 2v4a1 1 0 102 0V7a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>`,
        warning: `<svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>`,
        info: `<svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>`
    };
    
    notification.className += ` ${colors[type] || colors.info}`;
    
    notification.innerHTML = `
        <div class="p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    ${icons[type] || icons.info}
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900">${message}</p>
                </div>
                <button onclick="this.parentElement.parentElement.parentElement.remove()" class="flex-shrink-0 ml-3">
                    <svg class="w-4 h-4 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 10);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 5000);
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
    const modalContainer = modal.querySelector('.min-h-screen');
    if (e.target === modal || e.target === modalContainer) {
        closeModal();
    }
});
</script>
@endsection