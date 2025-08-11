
class PWAManager {
    constructor() {
        this.deferredPrompt = null;
        this.isInstalled = false;
        this.isOnline = navigator.onLine;
        this.serviceWorker = null;
        this.notificationPermission = 'default';
        
        this.init();
    }

    async init() {
        try {
            await this.registerServiceWorker();
            this.setupEventListeners();
            this.checkInstallation();
            this.setupOfflineIndicator();
            this.requestNotificationPermission();
        } catch (error) {
            console.error('PWA initialization failed:', error);
        }
    }

    async registerServiceWorker() {
        if ('serviceWorker' in navigator) {
            try {
                const registration = await navigator.serviceWorker.register('/sw.js');
                this.serviceWorker = registration;
                
                console.log('Service Worker registered successfully');
                
                registration.addEventListener('updatefound', () => {
                    const newWorker = registration.installing;
                    if (newWorker) {
                        newWorker.addEventListener('statechange', () => {
                            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                this.showUpdateAvailable();
                            }
                        });
                    }
                });

                navigator.serviceWorker.addEventListener('message', (event) => {
                    this.handleServiceWorkerMessage(event);
                });

            } catch (error) {
                console.error('Service Worker registration failed:', error);
            }
        }
    }

    setupEventListeners() {
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            this.deferredPrompt = e;
            this.showInstallPrompt();
        });

        window.addEventListener('appinstalled', () => {
            this.isInstalled = true;
            this.hideInstallPrompt();
            this.trackInstallation();
            console.log('PWA was installed');
        });

        window.addEventListener('online', () => {
            this.isOnline = true;
            this.updateOfflineIndicator();
            this.syncOfflineActions();
        });

        window.addEventListener('offline', () => {
            this.isOnline = false;
            this.updateOfflineIndicator();
        });

        document.addEventListener('visibilitychange', () => {
            if (!document.hidden && this.isOnline) {
                this.syncOfflineActions();
            }
        });
    }

    checkInstallation() {
        this.isInstalled = window.matchMedia('(display-mode: standalone)').matches ||
                          window.navigator.standalone === true;
        
        if (this.isInstalled) {
            console.log('PWA is running in installed mode');
        }
    }

    setupOfflineIndicator() {
        this.createOfflineIndicator();
        this.updateOfflineIndicator();
    }

    createOfflineIndicator() {
        if (document.getElementById('offline-indicator')) return;

        const indicator = document.createElement('div');
        indicator.id = 'offline-indicator';
        indicator.className = 'offline-indicator';
        indicator.innerHTML = `
            <div class="offline-content">
                <svg class="offline-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                </svg>
                <span>Sin conexión</span>
            </div>
        `;

        const style = document.createElement('style');
        style.textContent = `
            .offline-indicator {
                position: fixed;
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%);
                background: #ef4444;
                color: white;
                padding: 12px 20px;
                border-radius: 25px;
                font-size: 14px;
                font-weight: 500;
                z-index: 10000;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
                display: none;
                animation: slideUp 0.3s ease-out;
            }
            
            .offline-indicator.online {
                background: #10b981;
            }
            
            .offline-content {
                display: flex;
                align-items: center;
                gap: 8px;
            }
            
            .offline-icon {
                width: 16px;
                height: 16px;
            }
            
            @keyframes slideUp {
                from { transform: translateX(-50%) translateY(100px); opacity: 0; }
                to { transform: translateX(-50%) translateY(0); opacity: 1; }
            }
        `;

        document.head.appendChild(style);
        document.body.appendChild(indicator);
    }

    updateOfflineIndicator() {
        const indicator = document.getElementById('offline-indicator');
        if (!indicator) return;

        if (!this.isOnline) {
            indicator.style.display = 'block';
            indicator.className = 'offline-indicator';
            indicator.querySelector('span').textContent = 'Sin conexión';
            indicator.querySelector('path').setAttribute('d', 'M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z');
        } else {
            indicator.className = 'offline-indicator online';
            indicator.querySelector('span').textContent = 'Conectado';
            indicator.querySelector('path').setAttribute('d', 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z');
            
            setTimeout(() => {
                indicator.style.display = 'none';
            }, 3000);
        }
    }

    showInstallPrompt() {
        if (!this.deferredPrompt || this.isInstalled) return;

        const installPrompt = document.createElement('div');
        installPrompt.id = 'pwa-install-prompt';
        installPrompt.className = 'pwa-install-prompt';
        installPrompt.innerHTML = `
            <div class="install-prompt-content">
                <div class="install-prompt-header">
                    <svg class="install-icon" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M5,20H19V18H5M19,9H15V3H9V9H5L12,16L19,9Z" />
                    </svg>
                    <div class="install-text">
                        <h3>Instalar UGo Rewards</h3>
                        <p>Acceso rápido y funciona sin conexión</p>
                    </div>
                </div>
                <div class="install-actions">
                    <button class="install-button" id="install-app">Instalar</button>
                    <button class="install-dismiss" id="dismiss-install">Ahora no</button>
                </div>
            </div>
        `;

        const style = document.createElement('style');
        style.textContent = `
            .pwa-install-prompt {
                position: fixed;
                bottom: 20px;
                left: 20px;
                right: 20px;
                max-width: 400px;
                margin: 0 auto;
                background: white;
                border-radius: 12px;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
                z-index: 10000;
                animation: slideUp 0.3s ease-out;
            }
            
            .install-prompt-content {
                padding: 20px;
            }
            
            .install-prompt-header {
                display: flex;
                align-items: center;
                gap: 12px;
                margin-bottom: 16px;
            }
            
            .install-icon {
                width: 24px;
                height: 24px;
                color: #0ADD90;
                flex-shrink: 0;
            }
            
            .install-text h3 {
                margin: 0;
                font-size: 16px;
                font-weight: 600;
                color: #1f2937;
            }
            
            .install-text p {
                margin: 2px 0 0;
                font-size: 14px;
                color: #6b7280;
            }
            
            .install-actions {
                display: flex;
                gap: 12px;
            }
            
            .install-button {
                flex: 1;
                background: #0ADD90;
                color: white;
                border: none;
                padding: 12px 20px;
                border-radius: 8px;
                font-weight: 600;
                cursor: pointer;
                transition: background-color 0.2s;
            }
            
            .install-button:hover {
                background: #00AE6E;
            }
            
            .install-dismiss {
                background: #f3f4f6;
                color: #6b7280;
                border: none;
                padding: 12px 20px;
                border-radius: 8px;
                cursor: pointer;
                transition: background-color 0.2s;
            }
            
            .install-dismiss:hover {
                background: #e5e7eb;
            }
        `;

        document.head.appendChild(style);
        document.body.appendChild(installPrompt);

        document.getElementById('install-app').addEventListener('click', () => {
            this.triggerInstall();
        });

        document.getElementById('dismiss-install').addEventListener('click', () => {
            this.hideInstallPrompt();
            localStorage.setItem('pwa-install-dismissed', Date.now().toString());
        });

        setTimeout(() => {
            this.hideInstallPrompt();
        }, 10000);
    }

    hideInstallPrompt() {
        const prompt = document.getElementById('pwa-install-prompt');
        if (prompt) {
            prompt.remove();
        }
    }

    async triggerInstall() {
        if (!this.deferredPrompt) return;

        try {
            const result = await this.deferredPrompt.prompt();
            console.log('Install prompt result:', result.outcome);
            
            this.deferredPrompt = null;
            this.hideInstallPrompt();
            
            if (result.outcome === 'accepted') {
                this.trackInstallation();
            }
        } catch (error) {
            console.error('Install prompt failed:', error);
        }
    }

    async requestNotificationPermission() {
        if ('Notification' in window) {
            this.notificationPermission = await Notification.requestPermission();
            
            if (this.notificationPermission === 'granted') {
                await this.subscribeToPushNotifications();
            }
        }
    }

    async subscribeToPushNotifications() {
        if (!this.serviceWorker || this.notificationPermission !== 'granted') {
            return;
        }

        try {
            const subscription = await this.serviceWorker.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: this.urlBase64ToUint8Array(window.vapidPublicKey)
            });

            await fetch('/api/push-subscription', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    subscription: subscription.toJSON()
                })
            });

            console.log('Push notification subscription successful');
        } catch (error) {
            console.error('Push subscription failed:', error);
        }
    }

    urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/\-/g, '+')
            .replace(/_/g, '/');

        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);

        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }

    async syncOfflineActions() {
        if (!this.serviceWorker || !this.isOnline) return;

        try {
            await this.serviceWorker.ready;
            
            if ('sync' in window.ServiceWorkerRegistration.prototype) {
                await this.serviceWorker.sync.register('background-sync-orders');
                await this.serviceWorker.sync.register('background-sync-favorites');
            }
        } catch (error) {
            console.error('Background sync failed:', error);
        }
    }

    handleServiceWorkerMessage(event) {
        const { type, data } = event.data;

        switch (type) {
            case 'CACHE_UPDATED':
                console.log('Cache updated:', data);
                break;
                
            case 'OFFLINE_ACTION_QUEUED':
                this.showOfflineActionQueued(data);
                break;
                
            case 'SYNC_COMPLETED':
                this.showSyncCompleted(data);
                break;
        }
    }

    showOfflineActionQueued(data) {
        if (window.Livewire) {
            window.Livewire.dispatch('offlineActionQueued', data);
        }
    }

    showSyncCompleted(data) {
        if (window.Livewire) {
            window.Livewire.dispatch('syncCompleted', data);
        }
    }

    showUpdateAvailable() {
        const updateNotification = document.createElement('div');
        updateNotification.className = 'update-notification';
        updateNotification.innerHTML = `
            <div class="update-content">
                <p>Nueva versión disponible</p>
                <button onclick="window.location.reload()">Actualizar</button>
            </div>
        `;
        
        document.body.appendChild(updateNotification);
    }

    trackInstallation() {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'pwa_install', {
                event_category: 'PWA',
                event_label: 'App Installed'
            });
        }
    }

    getStatus() {
        return {
            isInstalled: this.isInstalled,
            isOnline: this.isOnline,
            serviceWorkerReady: !!this.serviceWorker,
            notificationPermission: this.notificationPermission,
            canInstall: !!this.deferredPrompt
        };
    }
}

document.addEventListener('DOMContentLoaded', () => {
    window.pwaManager = new PWAManager();
});

document.addEventListener('alpine:init', () => {
    Alpine.data('pwa', () => ({
        status: {
            isInstalled: false,
            isOnline: true,
            canInstall: false
        },
        
        init() {
            const updateStatus = () => {
                if (window.pwaManager) {
                    this.status = window.pwaManager.getStatus();
                }
            };
            
            updateStatus();
            setInterval(updateStatus, 1000);
        },
        
        install() {
            if (window.pwaManager) {
                window.pwaManager.triggerInstall();
            }
        }
    }));
});

export default PWAManager;