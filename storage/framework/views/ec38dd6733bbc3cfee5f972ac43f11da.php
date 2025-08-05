<?php $__env->startSection('title', 'Mi Perfil'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    
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
            <li class="text-gray-900 font-medium">Mi Perfil</li>
        </ol>
    </nav>

    
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Mi Perfil</h1>
        <p class="text-gray-600">Gestiona tu información personal y configuración de cuenta</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Información Personal</h2>
                    <button onclick="enableEdit()" id="edit-btn" class="btn-secondary text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar
                    </button>
                </div>

                <form id="profile-form" onsubmit="saveProfile(event)">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nombre" class="form-label">Nombre completo</label>
                            <input 
                                type="text" 
                                id="nombre" 
                                name="nombre" 
                                value="<?php echo e($employee ? $employee->nombre : ''); ?>" 
                                class="form-input" 
                                disabled>
                        </div>
                        
                        <div>
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="<?php echo e($employee ? $employee->email : ''); ?>" 
                                class="form-input" 
                                disabled>
                        </div>
                        
                        <div>
                            <label for="departamento" class="form-label">Departamento</label>
                            <input 
                                type="text" 
                                id="departamento" 
                                name="departamento" 
                                value="<?php echo e($employee ? $employee->departamento : ''); ?>" 
                                class="form-input" 
                                disabled>
                        </div>
                        
                        <div>
                            <label for="cargo" class="form-label">Cargo</label>
                            <input 
                                type="text" 
                                id="cargo" 
                                name="cargo" 
                                value="<?php echo e($employee ? $employee->cargo : ''); ?>" 
                                class="form-input" 
                                disabled>
                        </div>
                    </div>

                    <div class="mt-6 hidden" id="save-actions">
                        <div class="flex space-x-4">
                            <button type="submit" class="btn-primary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Guardar cambios
                            </button>
                            <button type="button" onclick="cancelEdit()" class="btn-secondary">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Resumen de Actividad</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600 mb-2"><?php echo e($employee ? $employee->orders->count() : 0); ?></div>
                        <div class="text-sm text-gray-600">Canjes realizados</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600 mb-2"><?php echo e(number_format($employee ? $employee->puntos_totales : 0)); ?></div>
                        <div class="text-sm text-gray-600">Puntos totales</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600 mb-2"><?php echo e($employee ? $employee->favorites->count() : 0); ?></div>
                        <div class="text-sm text-gray-600">Productos favoritos</div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="space-y-6">
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Mis Puntos</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">Puntos totales:</span>
                        <span class="text-lg font-bold text-gray-900"><?php echo e(number_format($employee ? $employee->puntos_totales : 0)); ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">Puntos canjeados:</span>
                        <span class="text-lg font-bold text-red-600"><?php echo e(number_format($employee ? $employee->puntos_canjeados : 0)); ?></span>
                    </div>
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-base font-semibold text-gray-900">Puntos disponibles:</span>
                            <span class="text-xl font-bold text-green-600"><?php echo e(number_format($employee ? ($employee->puntos_totales - $employee->puntos_canjeados) : 0)); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Cuenta</h3>
                
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-600">ID de empleado:</dt>
                        <dd class="text-sm text-gray-900 font-mono"><?php echo e($employee ? $employee->id_empleado : ''); ?></dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Rol de usuario:</dt>
                        <dd class="text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                <?php echo e($employee && $employee->is_admin ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'); ?>">
                                <?php echo e($employee && $employee->is_admin ? 'Administrador' : 'Usuario'); ?>

                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Miembro desde:</dt>
                        <dd class="text-sm text-gray-900"><?php echo e($employee ? $employee->created_at->format('d/m/Y') : ''); ?></dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Última actividad:</dt>
                        <dd class="text-sm text-gray-900"><?php echo e($employee ? $employee->updated_at->format('d/m/Y H:i') : ''); ?></dd>
                    </div>
                </dl>
            </div>

            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones Rápidas</h3>
                
                <div class="space-y-3">
                    <a href="<?php echo e(route('products.index')); ?>" class="w-full btn-primary text-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Explorar productos
                    </a>
                    
                    <a href="<?php echo e(route('orders.history')); ?>" class="w-full btn-secondary text-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Mi historial
                    </a>
                    
                    <a href="<?php echo e(route('favorites.index')); ?>" class="w-full btn-ghost text-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        Mis favoritos
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="message-container" class="fixed top-4 right-4 z-50 hidden">
    <div id="message" class="px-6 py-4 rounded-lg shadow-lg text-white"></div>
</div>

<script>
let originalData = {};

function enableEdit() {
    const inputs = document.querySelectorAll('#profile-form input');
    const editBtn = document.getElementById('edit-btn');
    const saveActions = document.getElementById('save-actions');
    
    // Store original data
    inputs.forEach(input => {
        originalData[input.name] = input.value;
        input.disabled = false;
        input.classList.add('focus:ring-2', 'focus:ring-blue-500');
    });
    
    editBtn.classList.add('hidden');
    saveActions.classList.remove('hidden');
}

function cancelEdit() {
    const inputs = document.querySelectorAll('#profile-form input');
    const editBtn = document.getElementById('edit-btn');
    const saveActions = document.getElementById('save-actions');
    
    // Restore original data
    inputs.forEach(input => {
        input.value = originalData[input.name];
        input.disabled = true;
        input.classList.remove('focus:ring-2', 'focus:ring-blue-500');
    });
    
    editBtn.classList.remove('hidden');
    saveActions.classList.add('hidden');
}

async function saveProfile(event) {
    event.preventDefault();
    
    const form = document.getElementById('profile-form');
    const saveBtn = form.querySelector('button[type="submit"]');
    const originalText = saveBtn.innerHTML;
    
    // Show loading state
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<div class="loading-spinner mr-2"></div>Guardando...';
    
    try {
        const formData = new FormData(form);
        
        const response = await fetch(`<?php echo e(route('profile.update')); ?>`, {
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
            showMessage('Perfil actualizado exitosamente', 'success');
            
            // Disable inputs and hide save actions
            const inputs = document.querySelectorAll('#profile-form input');
            const editBtn = document.getElementById('edit-btn');
            const saveActions = document.getElementById('save-actions');
            
            inputs.forEach(input => {
                input.disabled = true;
                input.classList.remove('focus:ring-2', 'focus:ring-blue-500');
            });
            
            editBtn.classList.remove('hidden');
            saveActions.classList.add('hidden');
            
        } else {
            showMessage(data.message || 'Error al actualizar el perfil', 'error');
        }
    } catch (error) {
        console.error('Profile update error:', error);
        showMessage('Error al actualizar el perfil', 'error');
    } finally {
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalText;
    }
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\User\sheets-backend\ugo-laravel\resources\views/auth/profile.blade.php ENDPATH**/ ?>