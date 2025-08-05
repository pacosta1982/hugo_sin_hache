@extends('layouts.app')

@section('title', 'Firebase Setup Guide')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">🔥 Firebase Authentication Setup Guide</h1>
            
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Important:</strong> Firebase authentication is currently using demo credentials. 
                            Follow this guide to set up a real Firebase project for production use.
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                
                <div class="border-l-4 border-blue-500 pl-4">
                    <h2 class="text-xl font-semibold text-gray-800 mb-3">Step 1: Create Firebase Project</h2>
                    <ol class="list-decimal list-inside space-y-2 text-gray-600">
                        <li>Go to <a href="https://console.firebase.google.com/" target="_blank" class="text-blue-600 hover:underline">Firebase Console</a></li>
                        <li>Click "Create a project" or "Add project"</li>
                        <li>Enter project name: <code class="bg-gray-100 px-2 py-1 rounded">ugo-sistema-puntos</code></li>
                        <li>Enable Google Analytics (optional)</li>
                        <li>Click "Create project"</li>
                    </ol>
                </div>

                
                <div class="border-l-4 border-green-500 pl-4">
                    <h2 class="text-xl font-semibold text-gray-800 mb-3">Step 2: Enable Authentication</h2>
                    <ol class="list-decimal list-inside space-y-2 text-gray-600">
                        <li>In Firebase Console, go to "Authentication" → "Sign-in method"</li>
                        <li>Enable "Email/Password" provider</li>
                        <li>Enable "Email link (passwordless sign-in)" if desired</li>
                        <li>Configure authorized domains (add your domain)</li>
                    </ol>
                </div>

                
                <div class="border-l-4 border-purple-500 pl-4">
                    <h2 class="text-xl font-semibold text-gray-800 mb-3">Step 3: Get Web App Configuration</h2>
                    <ol class="list-decimal list-inside space-y-2 text-gray-600">
                        <li>Go to Project Settings (gear icon)</li>
                        <li>Scroll to "Your apps" and click "Web" icon</li>
                        <li>Register app with nickname: <code class="bg-gray-100 px-2 py-1 rounded">ugo-web-app</code></li>
                        <li>Copy the configuration object</li>
                        <li>Update your <code class="bg-gray-100 px-2 py-1 rounded">.env</code> file with these values:</li>
                    </ol>
                    
                    <div class="mt-4 bg-gray-50 p-4 rounded-lg">
                        <pre class="text-sm text-gray-800">
VITE_FIREBASE_API_KEY=your_api_key_here
VITE_FIREBASE_AUTH_DOMAIN=your_project.firebaseapp.com
VITE_FIREBASE_PROJECT_ID=ugo-sistema-puntos
VITE_FIREBASE_STORAGE_BUCKET=your_project.appspot.com
VITE_FIREBASE_MESSAGING_SENDER_ID=your_sender_id
VITE_FIREBASE_APP_ID=your_app_id
                        </pre>
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