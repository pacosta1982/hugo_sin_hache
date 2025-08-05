import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Add CSRF token to all requests
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// Add Firebase token to requests if available
window.axios.interceptors.request.use(function (config) {
    const firebaseToken = localStorage.getItem('firebase_token');
    if (firebaseToken) {
        config.headers.Authorization = `Bearer ${firebaseToken}`;
    }
    return config;
}, function (error) {
    return Promise.reject(error);
});

// Handle 401 responses globally
window.axios.interceptors.response.use(function (response) {
    return response;
}, function (error) {
    if (error.response && error.response.status === 401) {
        localStorage.removeItem('firebase_token');
        window.location.href = '/login';
    }
    return Promise.reject(error);
});