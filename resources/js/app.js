import './bootstrap';


import { initializeApp } from 'firebase/app';
import { getAuth, onAuthStateChanged, signOut, signInWithEmailAndPassword, GoogleAuthProvider, signInWithPopup } from 'firebase/auth';


const firebaseConfig = {
    apiKey: import.meta.env.VITE_FIREBASE_API_KEY,
    authDomain: import.meta.env.VITE_FIREBASE_AUTH_DOMAIN,
    projectId: import.meta.env.VITE_FIREBASE_PROJECT_ID,
    storageBucket: import.meta.env.VITE_FIREBASE_STORAGE_BUCKET,
    messagingSenderId: import.meta.env.VITE_FIREBASE_MESSAGING_SENDER_ID,
    appId: import.meta.env.VITE_FIREBASE_APP_ID
};



const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
const googleProvider = new GoogleAuthProvider();


window.auth = auth;
window.signOut = signOut;
window.authInitialized = false;


window.signInWithGoogle = async function() {
    try {
        const result = await signInWithPopup(auth, googleProvider);
        

        const token = await result.user.getIdToken();
        localStorage.setItem('firebase_token', token);
        window.location.href = '/dashboard';
        
        return result;
    } catch (error) {
        
        let errorMessage = 'Error al iniciar sesión con Google';
        
        switch (error.code) {
            case 'auth/popup-closed-by-user':
                errorMessage = 'Inicio de sesión cancelado';
                break;
            case 'auth/popup-blocked':
                errorMessage = 'Popup bloqueado. Habilita popups para este sitio';
                break;
            case 'auth/network-request-failed':
                errorMessage = 'Error de conexión. Verifica tu internet';
                break;
            case 'auth/account-exists-with-different-credential':
                errorMessage = 'Ya existe una cuenta con este email usando otro método';
                break;
            default:
                errorMessage = `Error de Google: ${error.code}`;
                break;
        }
        
        showLoginError(errorMessage);
        throw error;
    }
};


onAuthStateChanged(auth, (user) => {
    

    window.authInitialized = true;
    
    if (user) {

        user.getIdToken().then((token) => {

            localStorage.setItem('firebase_token', token);
            

            setInterval(() => {
                user.getIdToken(true).then((refreshedToken) => {
                    localStorage.setItem('firebase_token', refreshedToken);
                });
            }, 50 * 60 * 1000);
            

            window.dispatchEvent(new CustomEvent('auth-completed', {
                detail: { user, token }
            }));
        });
    } else {

        localStorage.removeItem('firebase_token');
        


        if (!window.location.pathname.includes('/login')) {
            window.location.href = '/login';
        }
    }
});


window.waitForAuth = function() {
    return new Promise((resolve) => {
        if (window.authInitialized) {
            resolve(auth.currentUser);
        } else {
            const unsubscribe = onAuthStateChanged(auth, (user) => {
                unsubscribe();
                resolve(user);
            });
        }
    });
};


window.apiRequest = async function(url, options = {}) {
    const token = localStorage.getItem('firebase_token');
    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            ...(token && { 'Authorization': `Bearer ${token}` })
        }
    };

    const mergedOptions = {
        ...defaultOptions,
        ...options,
        headers: {
            ...defaultOptions.headers,
            ...options.headers
        }
    };

    try {
        const response = await fetch(url, mergedOptions);
        
        if (!response.ok) {
            if (response.status === 401) {

                localStorage.removeItem('firebase_token');
                window.location.href = '/login';
                return;
            }
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return await response.json();
    } catch (error) {
        throw error;
    }
};


window.formatCurrency = function(amount) {
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP'
    }).format(amount);
};

window.formatDate = function(date) {
    return new Intl.DateTimeFormat('es-CO', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    }).format(new Date(date));
};

window.formatPoints = function(points) {
    return new Intl.NumberFormat('es-CO').format(points);
};


window.showToast = function(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-md shadow-lg fade-in ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        type === 'warning' ? 'bg-yellow-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 5000);
};


window.showLoading = function() {
    const overlay = document.createElement('div');
    overlay.id = 'loading-overlay';
    overlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    overlay.innerHTML = `
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <div class="loading-spinner"></div>
            <span class="text-gray-700">Cargando...</span>
        </div>
    `;
    document.body.appendChild(overlay);
};

window.hideLoading = function() {
    const overlay = document.getElementById('loading-overlay');
    if (overlay) {
        overlay.remove();
    }
};


window.confirmAction = function(message, callback) {
    if (confirm(message)) {
        callback();
    }
};


document.addEventListener('DOMContentLoaded', function() {
    

    setupLoginForm();
    

    if (window.location.pathname === '/favoritos') {
        handleFavoritesPageAuth();
    }
    

    setupAuthenticatedNavigation();
    

    const mobileMenuButton = document.querySelector('.mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }


    const flashMessages = document.querySelectorAll('[data-flash-message]');
    flashMessages.forEach(message => {
        setTimeout(() => {
            message.style.opacity = '0';
            setTimeout(() => message.remove(), 300);
        }, 5000);
    });


    const forms = document.querySelectorAll('form[data-loading]');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<div class="loading-spinner mr-2"></div>Procesando...';
            }
        });
    });
});


function handleFavoritesPageAuth() {
    

    const hasEmployee = document.querySelector('[data-has-employee]');
    if (hasEmployee && hasEmployee.dataset.hasEmployee === 'true') {
        return;
    }
    
    let authCheckInterval;
    let hasTriedReload = false;
    

    authCheckInterval = setInterval(function() {
        const token = localStorage.getItem('firebase_token');
        const authInitialized = window.authInitialized;
        
        if (token && authInitialized && !hasTriedReload) {
            hasTriedReload = true;
            clearInterval(authCheckInterval);
            

            window.location.reload();
        }
    }, 500);
    

    setTimeout(function() {
        if (authCheckInterval) {
            clearInterval(authCheckInterval);
        }
    }, 10000);
}


function setupAuthenticatedNavigation() {

    const favoritesLinks = document.querySelectorAll('a[href*="/favoritos"]');
    
    favoritesLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const token = localStorage.getItem('firebase_token');
            
            if (token) {
                e.preventDefault();
                

                fetch('/favoritos', {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'text/html'
                    }
                }).then(response => {
                    if (response.ok) {
                        return response.text();
                    }
                    throw new Error('Failed to load favorites page');
                }).then(html => {

                    document.documentElement.innerHTML = html;

                    history.pushState(null, '', '/favoritos');
                }).catch(error => {

                    window.location.href = '/favoritos';
                });
            }
        });
    });
}


function setupLoginForm() {
    
    const loginForm = document.getElementById('manual-login');
    if (!loginForm) {
        return;
    }
    
    
    const loading = document.getElementById('auth-loading');
    const errorContainer = document.getElementById('error-message');
    
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const email = document.getElementById('login-email').value;
        const password = document.getElementById('login-password').value;
        
        

        hideLoginError();
        

        if (loading && loginForm) {
            loading.classList.remove('hidden');
            loginForm.classList.add('hidden');
        }
        
        try {
            const userCredential = await signInWithEmailAndPassword(auth, email, password);
            

            const token = await userCredential.user.getIdToken();
            localStorage.setItem('firebase_token', token);
            window.location.href = '/dashboard';
            
        } catch (error) {
            

            if (loading && loginForm) {
                loading.classList.add('hidden');
                loginForm.classList.remove('hidden');
            }
            

            let errorMessage = getLoginErrorMessage(error.code);
            showLoginError(errorMessage);
        }
    });
}

function getLoginErrorMessage(errorCode) {
    switch (errorCode) {
        case 'auth/user-not-found':
            return 'No existe una cuenta con este email';
        case 'auth/wrong-password':
            return 'Contraseña incorrecta';
        case 'auth/invalid-email':
            return 'Email inválido';
        case 'auth/user-disabled':
            return 'Esta cuenta ha sido deshabilitada';
        case 'auth/too-many-requests':
            return 'Demasiados intentos. Inténtalo más tarde';
        case 'auth/network-request-failed':
            return 'Error de conexión. Verifica tu internet';
        case 'auth/invalid-credential':
            return 'Credenciales inválidas. Verifica tu email y contraseña';
        default:
            return `Error de autenticación: ${errorCode}`;
    }
}

function showLoginError(message) {
    const errorContainer = document.getElementById('error-message');
    const errorText = document.getElementById('error-text');
    if (errorContainer && errorText) {
        errorText.textContent = message;
        errorContainer.classList.remove('hidden');
    }
}

function hideLoginError() {
    const errorContainer = document.getElementById('error-message');
    if (errorContainer) {
        errorContainer.classList.add('hidden');
    }
}