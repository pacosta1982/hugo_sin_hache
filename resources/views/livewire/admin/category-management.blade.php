<div>
    {{-- Category Management Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Gestión de Categorías</h2>
            <p class="text-gray-600">Organiza productos en categorías jerárquicas</p>
        </div>
        <button wire:click="openCreateModal" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nueva Categoría
        </button>
    </div>

    {{-- Categories Grid --}}
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        @if(count($categories) > 0)
            <div class="divide-y divide-gray-200">
                @foreach($categories as $category)
                    <div class="p-6 hover:bg-gray-50 transition-colors duration-150">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                {{-- Category Color & Icon --}}
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: {{ $category['color'] }}">
                                        @if($category['icon'])
                                            <i class="fas fa-{{ $category['icon'] }} text-white text-lg"></i>
                                        @else
                                            <i class="fas fa-folder text-white text-lg"></i>
                                        @endif
                                    </div>
                                </div>
                                
                                {{-- Category Info --}}
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <h3 class="text-lg font-medium text-gray-900">
                                            {{ $category['name'] }}
                                        </h3>
                                        
                                        {{-- Status Badge --}}
                                        @if($category['is_active'])
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Activa
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Inactiva
                                            </span>
                                        @endif
                                        
                                        {{-- Products Count --}}
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $category['products_count'] ?? 0 }} productos
                                        </span>
                                    </div>
                                    
                                    @if($category['description'])
                                        <p class="mt-1 text-sm text-gray-500">{{ $category['description'] }}</p>
                                    @endif
                                    
                                    {{-- Parent Category --}}
                                    @if($category['parent'])
                                        <div class="mt-1 flex items-center text-xs text-gray-400">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                                            </svg>
                                            Subcategoría de: {{ $category['parent']['name'] }}
                                        </div>
                                    @endif
                                    
                                    {{-- Subcategories --}}
                                    @if(count($category['children'] ?? []) > 0)
                                        <div class="mt-2 flex items-center text-xs text-gray-500">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 7a2 2 0 012-2h10a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                            {{ count($category['children']) }} subcategorías
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            {{-- Action Buttons --}}
                            <div class="flex items-center space-x-2">
                                {{-- Toggle Active --}}
                                <button wire:click="toggleActive({{ $category['id'] }})" 
                                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    @if($category['is_active'])
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"/>
                                        </svg>
                                        Desactivar
                                    @else
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Activar
                                    @endif
                                </button>
                                
                                {{-- Edit Button --}}
                                <button wire:click="openEditModal({{ $category['id'] }})" 
                                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Editar
                                </button>
                                
                                {{-- Delete Button --}}
                                <button wire:click="deleteCategory({{ $category['id'] }})" 
                                        wire:confirm="¿Estás seguro de que quieres eliminar esta categoría?"
                                        class="inline-flex items-center px-3 py-1.5 border border-red-300 text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 7a2 2 0 012-2h10a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay categorías</h3>
                <p class="mt-1 text-sm text-gray-500">Comienza creando tu primera categoría de productos.</p>
                <div class="mt-6">
                    <button wire:click="openCreateModal" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Nueva Categoría
                    </button>
                </div>
            </div>
        @endif
    </div>

    {{-- Modal for Create/Edit --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit="saveCategory">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="w-full mt-3 text-center sm:mt-0 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                        {{ $modalTitle }}
                                    </h3>
                                    
                                    <div class="mt-4 space-y-4">
                                        {{-- Category Name --}}
                                        <div>
                                            <label for="name" class="block text-sm font-medium text-gray-700">Nombre *</label>
                                            <input type="text" wire:model="name" id="name" 
                                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" 
                                                   placeholder="Nombre de la categoría" required>
                                            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                        
                                        {{-- Description --}}
                                        <div>
                                            <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                                            <textarea wire:model="description" id="description" rows="3"
                                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" 
                                                      placeholder="Descripción opcional de la categoría"></textarea>
                                            @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                        
                                        {{-- Icon and Color Row --}}
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label for="icon" class="block text-sm font-medium text-gray-700">Icono</label>
                                                <input type="text" wire:model="icon" id="icon" 
                                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" 
                                                       placeholder="shopping-bag">
                                                <p class="mt-1 text-xs text-gray-500">Font Awesome icon name</p>
                                                @error('icon') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                            </div>
                                            
                                            <div>
                                                <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
                                                <input type="color" wire:model="color" id="color" 
                                                       class="mt-1 block w-full h-10 border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                                                @error('color') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                        
                                        {{-- Parent Category and Sort Order --}}
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label for="parent_id" class="block text-sm font-medium text-gray-700">Categoría Padre</label>
                                                <select wire:model="parent_id" id="parent_id" 
                                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                                    <option value="">Categoría principal</option>
                                                    @foreach($parentCategories as $parentCategory)
                                                        <option value="{{ $parentCategory->id }}">{{ $parentCategory->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('parent_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                            </div>
                                            
                                            <div>
                                                <label for="sort_order" class="block text-sm font-medium text-gray-700">Orden</label>
                                                <input type="number" wire:model="sort_order" id="sort_order" min="0"
                                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" 
                                                       placeholder="0">
                                                @error('sort_order') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                        
                                        {{-- Active Checkbox --}}
                                        <div class="flex items-center">
                                            <input type="checkbox" wire:model="is_active" id="is_active" 
                                                   class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                            <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                                Categoría activa
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" 
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                                {{ $isEditing ? 'Actualizar' : 'Crear' }} Categoría
                            </button>
                            <button type="button" wire:click="closeModal"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
