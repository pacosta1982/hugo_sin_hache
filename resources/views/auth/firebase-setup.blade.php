@extends('layouts.app')

@section('title', 'Firebase Setup Guide')

@section('content')
<div class="container mx-auto px-4 py-8 space-y-8">
    
    <div class="relative fade-in-up">
        <div class="bg-gradient-to-r from-orange-50/80 via-red-50/80 to-yellow-50/60 rounded-3xl p-8 border border-orange-100 shimmer">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between">
                <div class="flex items-center space-x-6">
                    <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-red-500 rounded-3xl flex items-center justify-center shadow-lg">
                        <svg class="w-12 h-12 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M5.803 21.503C6.198 21.817 6.669 22 7.142 22h10.716c.473 0 .944-.183 1.339-.497L12 12zm8.493-13.13L16.6 6.1 12 12l-4.6-5.9 2.304-2.273c.24-.24.558-.375.896-.375.338 0 .656.135.896.375zM2 12l5.5 9.5L12 12zm12 0l5.5 9.5L15.5 2.5z"/>
                        </svg>
                    </div>
                    
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">
                            🔥 Firebase Authentication Setup
                        </h1>
                        <p class="text-lg text-gray-600 mb-3">
                            Configuración paso a paso para Firebase Auth
                        </p>
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Guía completa
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                6 pasos fáciles
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="absolute -top-4 -right-4 w-32 h-32 bg-orange-200/30 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-red-200/30 rounded-full blur-3xl"></div>
    </div>

    <div class="card card-entrance hover-glow">
        <div class="card-body">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">⚠️ Configuración Requerida</h3>
                    <p class="text-gray-700 leading-relaxed">
                        <strong>Importante:</strong> Firebase authentication está usando credenciales de demostración. 
                        Sigue esta guía para configurar un proyecto Firebase real para uso en producción y tener acceso completo a todas las funcionalidades.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8">
        
        <div class="card group hover:shadow-xl transition-all duration-300 stagger-item interactive-card">
            <div class="card-header">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center text-white font-bold text-lg shadow-lg group-hover:scale-110 transition-transform">
                        1
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Crear Proyecto Firebase</h2>
                        <p class="text-gray-600">Configura tu proyecto en Firebase Console</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="text-blue-600 font-bold text-sm">1</span>
                        </div>
                        <p class="text-gray-700">Ve a <a href="https://console.firebase.google.com/" target="_blank" class="text-blue-600 hover:text-blue-800 font-semibold underline decoration-2 underline-offset-2">Firebase Console</a></p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="text-blue-600 font-bold text-sm">2</span>
                        </div>
                        <p class="text-gray-700">Haz clic en "Create a project" o "Add project"</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="text-blue-600 font-bold text-sm">3</span>
                        </div>
                        <p class="text-gray-700">Ingresa el nombre del proyecto: <code class="bg-blue-50 text-blue-800 px-3 py-1 rounded-lg font-mono text-sm">ugo-sistema-puntos</code></p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="text-blue-600 font-bold text-sm">4</span>
                        </div>
                        <p class="text-gray-700">Habilita Google Analytics (opcional)</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="text-blue-600 font-bold text-sm">5</span>
                        </div>
                        <p class="text-gray-700">Haz clic en "Create project"</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card group hover:shadow-xl transition-all duration-300 stagger-item interactive-card">
            <div class="card-header">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center text-white font-bold text-lg shadow-lg group-hover:scale-110 transition-transform">
                        2
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Habilitar Autenticación</h2>
                        <p class="text-gray-600">Configura los métodos de inicio de sesión</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="text-green-600 font-bold text-sm">1</span>
                        </div>
                        <p class="text-gray-700">En Firebase Console, ve a "Authentication" → "Sign-in method"</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="text-green-600 font-bold text-sm">2</span>
                        </div>
                        <p class="text-gray-700">Habilita el proveedor "Email/Password"</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="text-green-600 font-bold text-sm">3</span>
                        </div>
                        <p class="text-gray-700">Habilita "Google" como proveedor (opcional)</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="text-green-600 font-bold text-sm">4</span>
                        </div>
                        <p class="text-gray-700">Configura dominios autorizados (agrega tu dominio)</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card group hover:shadow-xl transition-all duration-300 stagger-item interactive-card">
            <div class="card-header">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center text-white font-bold text-lg shadow-lg group-hover:scale-110 transition-transform">
                        3
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Configuración Web App</h2>
                        <p class="text-gray-600">Obtén las credenciales de frontend</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="space-y-6">
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-purple-600 font-bold text-sm">1</span>
                            </div>
                            <p class="text-gray-700">Ve a Project Settings (ícono de engranaje)</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-purple-600 font-bold text-sm">2</span>
                            </div>
                            <p class="text-gray-700">Desplázate a "Your apps" y haz clic en el ícono "Web"</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-purple-600 font-bold text-sm">3</span>
                            </div>
                            <p class="text-gray-700">Registra la app con nombre: <code class="bg-purple-50 text-purple-800 px-3 py-1 rounded-lg font-mono text-sm">ugo-web-app</code></p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-purple-600 font-bold text-sm">4</span>
                            </div>
                            <p class="text-gray-700">Copia la configuración y actualiza tu archivo <code class="bg-purple-50 text-purple-800 px-3 py-1 rounded-lg font-mono text-sm">.env</code>:</p>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-gray-900 to-gray-800 p-6 rounded-2xl border border-gray-700 shadow-xl">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            </div>
                            <span class="text-gray-400 text-sm">.env</span>
                        </div>
                        <pre class="text-green-400 text-sm font-mono leading-relaxed"><code>VITE_FIREBASE_API_KEY=your_api_key_here
VITE_FIREBASE_AUTH_DOMAIN=your_project.firebaseapp.com
VITE_FIREBASE_PROJECT_ID=ugo-sistema-puntos
VITE_FIREBASE_STORAGE_BUCKET=your_project.appspot.com
VITE_FIREBASE_MESSAGING_SENDER_ID=your_sender_id
VITE_FIREBASE_APP_ID=your_app_id</code></pre>
                    </div>
                </div>
            </div>
        </div>

                
                <div class="border-l-4 border-orange-500 pl-4">
                    <h2 class="text-xl font-semibold text-gray-800 mb-3">Step 4: Generate Service Account Key</h2>
                    <ol class="list-decimal list-inside space-y-2 text-gray-600">
                        <li>In Firebase Console, go to Project Settings → "Service accounts"</li>
                        <li>Click "Generate new private key"</li>
                        <li>Download the JSON file</li>
                        <li>Replace <code class="bg-gray-100 px-2 py-1 rounded">firebase-admin-credentials.json</code> with the downloaded file</li>
                        <li>Update your <code class="bg-gray-100 px-2 py-1 rounded">.env</code> file:</li>
                    </ol>
                    
                    <div class="mt-4 bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-800">
FIREBASE_CREDENTIALS=firebase-admin-credentials.json
FIREBASE_PROJECT_ID=your_actual_project_id
                        </pre>
                    </div>
                </div>

                
                <div class="border-l-4 border-red-500 pl-4">
                    <h2 class="text-xl font-semibold text-gray-800 mb-3">Step 5: Create Admin User</h2>
                    <ol class="list-decimal list-inside space-y-2 text-gray-600">
                        <li>Go to "Authentication" → "Users" in Firebase Console</li>
                        <li>Click "Add user"</li>
                        <li>Enter admin email and password</li>
                        <li>Add this user to your database with admin role:</li>
                    </ol>

                    <div class="mt-4 bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-800">
INSERT INTO employees (id_empleado, nombre, email, rol_usuario, puntos_totales, puntos_canjeados)
VALUES ('firebase_uid_here', 'Administrator', 'admin@yourcompany.com', 'Administrador', 0, 0);
                        </pre>
                    </div>
                </div>

                
                <div class="border-l-4 border-indigo-500 pl-4">
                    <h2 class="text-xl font-semibold text-gray-800 mb-3">Step 6: Test Configuration</h2>
                    <ol class="list-decimal list-inside space-y-2 text-gray-600">
                        <li>Restart your development server: <code class="bg-gray-100 px-2 py-1 rounded">npm run dev</code></li>
                        <li>Clear browser cache and localStorage</li>
                        <li>Try logging in with your test user</li>
                        <li>Check browser console for any errors</li>
                        <li>Verify JWT token validation works</li>
                    </ol>
                </div>

                
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">🔧 Current Configuration Status</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="font-medium text-gray-700 mb-2">Frontend Config</h3>
                            <ul class="space-y-1 text-sm">
                                <li class="flex items-center">
                                    <span class="{{ env('VITE_FIREBASE_API_KEY') ? 'text-green-500' : 'text-red-500' }}">●</span>
                                    <span class="ml-2">API Key</span>
                                </li>
                                <li class="flex items-center">
                                    <span class="{{ env('VITE_FIREBASE_AUTH_DOMAIN') ? 'text-green-500' : 'text-red-500' }}">●</span>
                                    <span class="ml-2">Auth Domain</span>
                                </li>
                                <li class="flex items-center">
                                    <span class="{{ env('VITE_FIREBASE_PROJECT_ID') ? 'text-green-500' : 'text-red-500' }}">●</span>
                                    <span class="ml-2">Project ID</span>
                                </li>
                            </ul>
                        </div>
                        
                        <div>
                            <h3 class="font-medium text-gray-700 mb-2">Backend Config</h3>
                            <ul class="space-y-1 text-sm">
                                <li class="flex items-center">
                                    <span class="{{ file_exists(env('FIREBASE_CREDENTIALS', '')) ? 'text-green-500' : 'text-red-500' }}">●</span>
                                    <span class="ml-2">Service Account</span>
                                </li>
                                <li class="flex items-center">
                                    <span class="{{ env('FIREBASE_PROJECT_ID') ? 'text-green-500' : 'text-red-500' }}">●</span>
                                    <span class="ml-2">Project ID</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                
                <div class="bg-blue-50 p-6 rounded-lg">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">⚡ Quick Commands</h2>
                    
                    <div class="space-y-3">
                        <div>
                            <h3 class="font-medium text-gray-700">Test Firebase Connection:</h3>
                            <code class="bg-gray-800 text-green-400 px-3 py-2 rounded block mt-1">php artisan tinker</code>
                            <code class="bg-gray-800 text-green-400 px-3 py-2 rounded block mt-1">>>> app(\Kreait\Firebase\Auth::class)->listUsers(1000, 1);</code>
                        </div>
                        
                        <div>
                            <h3 class="font-medium text-gray-700">Rebuild Frontend:</h3>
                            <code class="bg-gray-800 text-green-400 px-3 py-2 rounded block mt-1">npm run build</code>
                        </div>
                        
                        <div>
                            <h3 class="font-medium text-gray-700">Check Current Configuration:</h3>
                            <code class="bg-gray-800 text-green-400 px-3 py-2 rounded block mt-1">php artisan config:show firebase</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection