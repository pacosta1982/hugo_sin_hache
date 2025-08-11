@extends('layouts.app')

@section('title', 'Gestión de Empleados - Admin')

@section('content')

<div id="admin-auth-check" style="display: none;">
    <div class="min-h-screen flex items-center justify-center">
        <div class="text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-itti-primary mx-auto mb-4"></div>
            <p class="text-gray-600">Verificando permisos de administrador...</p>
        </div>
    </div>
</div>

<div id="admin-content" style="display: none;">
    @livewire('admin.employee-management')
</div>

<script>
// Admin Authentication Check
document.addEventListener('DOMContentLoaded', function() {
    const authCheck = document.getElementById('admin-auth-check');
    const adminContent = document.getElementById('admin-content');
    
    // Show loading while checking auth
    authCheck.style.display = 'block';
    
    async function checkAdminAuth() {
        const token = localStorage.getItem('firebase_token');
        
        if (!token) {
            window.location.href = '/login';
            return;
        }
        
        try {
            const response = await fetch('/api/me', {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) {
                throw new Error('Authentication failed');
            }
            
            const data = await response.json();
            
            if (!data.success || !data.employee) {
                throw new Error('User not found');
            }
            
            const employee = data.employee;
            const isAdmin = employee.rol_usuario === 'Administrador' || employee.is_admin;
            
            if (!isAdmin) {
                authCheck.innerHTML = `
                    <div class="min-h-screen flex items-center justify-center">
                        <div class="text-center">
                            <div class="text-red-600 text-6xl mb-4">🚫</div>
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">Acceso Denegado</h1>
                            <p class="text-gray-600 mb-4">No tienes permisos de administrador.</p>
                            <a href="/admin" class="btn-primary">Volver al Dashboard</a>
                        </div>
                    </div>
                `;
                return;
            }
            
            authCheck.style.display = 'none';
            adminContent.style.display = 'block';
            
        } catch (error) {
            console.error('Admin auth check failed:', error);
            window.location.href = '/login';
        }
    }
    
    if (window.authInitialized && localStorage.getItem('firebase_token')) {
        setTimeout(checkAdminAuth, 500);
    } else {
        window.addEventListener('auth-completed', checkAdminAuth);
        
        let authCheckCount = 0;
        const authInterval = setInterval(() => {
            authCheckCount++;
            if (localStorage.getItem('firebase_token') || authCheckCount > 10) {
                clearInterval(authInterval);
                if (localStorage.getItem('firebase_token')) {
                    checkAdminAuth();
                }
            }
        }, 1000);
    }
});
</script>
@endsection