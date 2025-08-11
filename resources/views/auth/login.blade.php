<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Iniciar Sesión - {{ config('app.name', 'UGo') }}</title>

    
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-itti-primary/5 via-white to-itti-secondary/5 min-h-screen">
    
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-itti-primary/10 to-itti-secondary/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-br from-itti-secondary/10 to-itti-primary/10 rounded-full blur-3xl"></div>
        <div class="absolute top-20 left-20 w-32 h-32 bg-itti-primary/5 rounded-full blur-2xl"></div>
        <div class="absolute bottom-20 right-20 w-40 h-40 bg-itti-secondary/5 rounded-full blur-2xl"></div>
    </div>

    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="max-w-sm w-full mx-auto space-y-6">
            
            <div class="text-center">
                <div class="mx-auto flex justify-center mb-8">
                    <div class="w-20 h-20 bg-gradient-to-br from-itti-primary via-itti-secondary to-itti-accent rounded-2xl flex items-center justify-center shadow-2xl ring-4 ring-white/50 transform hover:scale-105 transition-all duration-300">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center">
                            <div class="w-6 h-6 bg-gradient-to-r from-itti-primary to-itti-secondary rounded-md"></div>
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-itti-primary to-itti-secondary bg-clip-text text-transparent">
                        Bienvenido a UGo
                    </h1>
                    <p class="text-lg text-gray-600 font-medium">
                        Sistema de Puntos y Recompensas
                    </p>
                    <div class="flex items-center justify-center space-x-2 text-sm text-gray-500 bg-gray-50 rounded-full px-4 py-2 inline-flex">
                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Acceso seguro con Firebase Auth</span>
                    </div>
                </div>
            </div>

        
            <div class="bg-white/80 backdrop-blur-xl p-4 sm:p-6 rounded-3xl shadow-2xl border border-white/30 space-y-4 relative overflow-hidden w-full">
                <div class="absolute inset-0 opacity-5">
                    <div class="absolute inset-0 bg-gradient-to-br from-itti-primary/10 to-itti-secondary/10"></div>
                </div>
                <div class="relative z-10 space-y-4">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-itti-primary to-itti-secondary rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg ring-4 ring-itti-primary/10">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                            Iniciar Sesión
                        </h3>
                        <p class="text-gray-600 text-sm">
                            Accede a tu cuenta para gestionar tus puntos y recompensas
                        </p>
                    </div>

                    <button type="button" onclick="signInWithGoogle()" class="w-full group relative overflow-hidden transform hover:scale-[1.02] transition-all duration-300 rounded-2xl">
                        <div class="absolute inset-0 bg-gradient-to-r from-itti-primary to-itti-secondary shadow-lg group-hover:shadow-xl rounded-2xl"></div>
                        <div class="relative flex items-center justify-center px-3 py-2.5 text-white font-medium text-sm rounded-2xl">
                            <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            Continuar con Google
                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </div>
                    </button>

                    <div class="relative my-4">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="px-4 py-1.5 bg-white text-gray-500 text-xs font-medium rounded-full shadow-sm border border-gray-200">
                                o inicia sesión con email
                            </span>
                        </div>
                    </div>

                <form id="manual-login" class="space-y-3">
                    <div class="space-y-3">
                        <div class="form-group">
                            <label class="form-label flex items-center font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 mr-2 text-itti-primary" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                                Email corporativo
                            </label>
                            <div class="relative">
                                <input type="email" 
                                       id="login-email" 
                                       class="w-full pl-8 pr-3 py-2.5 text-sm rounded-xl border-2 border-gray-200 focus:border-itti-primary focus:ring-2 focus:ring-itti-primary/20 transition-all duration-300 bg-white shadow-sm" 
                                       placeholder="tu-email@empresa.com" 
                                       required>
                                <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label flex items-center font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 mr-2 text-itti-primary" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                </svg>
                                Contraseña
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       id="login-password" 
                                       class="w-full pl-8 pr-3 py-2.5 text-sm rounded-xl border-2 border-gray-200 focus:border-itti-primary focus:ring-2 focus:ring-itti-primary/20 transition-all duration-300 bg-white shadow-sm" 
                                       placeholder="••••••••" 
                                       required>
                                <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full group relative overflow-hidden rounded-2xl">
                        <div class="absolute inset-0 bg-gradient-to-r from-itti-primary to-itti-secondary rounded-2xl"></div>
                        <div class="relative flex items-center justify-center px-3 py-2.5 text-white font-medium text-sm rounded-2xl transition-all duration-300 group-hover:scale-[1.02]">
                            <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Iniciar Sesión
                            <div class="absolute inset-0 bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                    </button>
                </form>

                <div id="error-message" class="hidden">
                    <div class="bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-2xl p-4">
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 mr-3">
                                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold text-red-800 mb-1">Error de autenticación</h4>
                                <p id="error-text" class="text-sm text-red-700"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="auth-loading" class="hidden text-center py-12">
                    <div class="w-16 h-16 bg-gradient-to-r from-itti-primary to-itti-secondary rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse">
                        <div class="w-6 h-6 border-3 border-white border-t-transparent rounded-full animate-spin"></div>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Verificando credenciales</h3>
                    <p class="text-sm text-gray-600">Conectando con el sistema de autenticación...</p>
                    <div class="mt-4 flex justify-center space-x-1">
                        <div class="w-2 h-2 bg-itti-primary rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                        <div class="w-2 h-2 bg-itti-primary rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                        <div class="w-2 h-2 bg-itti-primary rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                    </div>
                </div>
                </div>
            </div>

            <div class="text-center space-y-4">
                <div class="flex items-center justify-center space-x-6 text-xs text-gray-500">
                    <div class="flex items-center space-x-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>Seguro</span>
                    </div>
                    <div class="flex items-center space-x-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Verificado</span>
                    </div>
                    <div class="flex items-center space-x-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Firebase Auth</span>
                    </div>
                </div>
                
                <p class="text-sm text-gray-600 bg-gray-50 rounded-xl px-4 py-2 inline-block">
                    ¿Problemas para acceder? 
                    <a href="mailto:admin@empresa.com" class="text-itti-primary hover:text-itti-secondary font-semibold transition-colors">
                        Contacta al administrador
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script type="module">
        // Firebase configuration and authentication handling
        import { initializeApp } from 'https://www.gstatic.com/firebasejs/9.22.0/firebase-app.js';
        import { getAuth, GoogleAuthProvider, signInWithPopup, signInWithEmailAndPassword, onAuthStateChanged } from 'https://www.gstatic.com/firebasejs/9.22.0/firebase-auth.js';

        console.log('piter');
        

        // Firebase configuration
        const firebaseConfig = {
            apiKey: import.meta.env.VITE_FIREBASE_API_KEY || "demo-api-key",
            authDomain: import.meta.env.VITE_FIREBASE_AUTH_DOMAIN || "demo.firebaseapp.com",
            projectId: import.meta.env.VITE_FIREBASE_PROJECT_ID || "demo-project",
            storageBucket: import.meta.env.VITE_FIREBASE_STORAGE_BUCKET || "demo.appspot.com",
            messagingSenderId: import.meta.env.VITE_FIREBASE_MESSAGING_SENDER_ID || "123456789",
            appId: import.meta.env.VITE_FIREBASE_APP_ID || "demo-app-id"
        };

        console.log(firebaseConfig);

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);

        // Google Sign In
        window.signInWithGoogle = async function() {
            const provider = new GoogleAuthProvider();
            showAuthLoading(true);
            
            try {
                const result = await signInWithPopup(auth, provider);
                const user = result.user;
                
                // Get Firebase ID token
                const token = await user.getIdToken();
                localStorage.setItem('firebase_token', token);
                
                // Redirect to dashboard
                window.location.href = '/dashboard';
            } catch (error) {
                showAuthError('Error al iniciar sesión con Google: ' + error.message);
                showAuthLoading(false);
            }
        };

        // Email/Password Sign In
        document.getElementById('manual-login').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('login-email').value;
            const password = document.getElementById('login-password').value;
            
            if (!email || !password) {
                showAuthError('Por favor completa todos los campos');
                return;
            }
            
            showAuthLoading(true);
            
            try {
                const userCredential = await signInWithEmailAndPassword(auth, email, password);
                const user = userCredential.user;
                
                // Get Firebase ID token
                const token = await user.getIdToken();
                localStorage.setItem('firebase_token', token);
                
                // Redirect to dashboard
                window.location.href = '/dashboard';
            } catch (error) {
                showAuthError('Credenciales inválidas. Verifica tu email y contraseña.');
                showAuthLoading(false);
            }
        });

        // Auth state observer
        onAuthStateChanged(auth, (user) => {
            if (user) {
                // User is signed in, redirect to dashboard
                console.log('User is signed in:', user.email);
            } else {
                console.log('User is signed out');
            }
        });

        // Utility functions
        function showAuthLoading(show) {
            const loadingEl = document.getElementById('auth-loading');
            const formEl = document.getElementById('manual-login');
            const googleBtn = document.querySelector('button[onclick="signInWithGoogle()"]');
            
            if (show) {
                loadingEl.classList.remove('hidden');
                formEl.style.opacity = '0.5';
                formEl.style.pointerEvents = 'none';
                googleBtn.style.opacity = '0.5';
                googleBtn.style.pointerEvents = 'none';
            } else {
                loadingEl.classList.add('hidden');
                formEl.style.opacity = '1';
                formEl.style.pointerEvents = 'auto';
                googleBtn.style.opacity = '1';
                googleBtn.style.pointerEvents = 'auto';
            }
        }

        function showAuthError(message) {
            const errorEl = document.getElementById('error-message');
            const errorTextEl = document.getElementById('error-text');
            
            errorTextEl.textContent = message;
            errorEl.classList.remove('hidden');
            
            // Hide error after 5 seconds
            setTimeout(() => {
                errorEl.classList.add('hidden');
            }, 5000);
        }
    </script>

</body>
</html>