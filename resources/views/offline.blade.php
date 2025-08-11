<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sin conexión - UGo Rewards</title>
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #0ADD90 0%, #00AE6E 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        
        .offline-container {
            text-align: center;
            max-width: 500px;
            padding: 40px 20px;
        }
        
        .offline-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 30px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s infinite;
        }
        
        .offline-icon svg {
            width: 60px;
            height: 60px;
            fill: white;
        }
        
        h1 {
            font-size: 2.5rem;
            margin: 0 0 20px;
            font-weight: 600;
        }
        
        p {
            font-size: 1.1rem;
            margin: 0 0 30px;
            opacity: 0.9;
            line-height: 1.6;
        }
        
        .retry-button {
            background: white;
            color: #00AE6E;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            margin: 0 10px 20px;
        }
        
        .retry-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .offline-features {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 20px;
            margin-top: 30px;
            text-align: left;
        }
        
        .offline-features h3 {
            margin: 0 0 15px;
            font-size: 1.2rem;
        }
        
        .offline-features ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .offline-features li {
            padding: 5px 0;
            padding-left: 25px;
            position: relative;
        }
        
        .offline-features li:before {
            content: '✓';
            position: absolute;
            left: 0;
            top: 5px;
            color: #0ADD90;
            font-weight: bold;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .connection-status {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 15px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .status-indicator {
            width: 10px;
            height: 10px;
            background: #ff4444;
            border-radius: 50%;
        }
        
        .status-indicator.online {
            background: #44ff44;
        }
        
        @media (max-width: 600px) {
            .offline-container {
                padding: 20px 15px;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .offline-icon {
                width: 100px;
                height: 100px;
            }
            
            .offline-icon svg {
                width: 50px;
                height: 50px;
            }
        }
    </style>
</head>
<body>
    <div class="connection-status" id="connectionStatus">
        <div class="status-indicator" id="statusIndicator"></div>
        <span id="statusText">Sin conexión</span>
    </div>

    <div class="offline-container">
        <div class="offline-icon">
            <svg viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM11 17l-5-5 1.41-1.41L11 14.17l7.59-7.59L20 8l-9 9z"/>
            </svg>
        </div>
        
        <h1>Sin conexión a internet</h1>
        <p>No te preocupes, UGo Rewards funciona parcialmente sin conexión. Puedes ver tu información guardada y realizar algunas acciones que se sincronizarán cuando recuperes la conexión.</p>
        
        <button class="retry-button" onclick="location.reload()">
            Reintentar conexión
        </button>
        
        <button class="retry-button" onclick="goToDashboard()" style="background: rgba(255,255,255,0.2); color: white;">
            Ir al Dashboard
        </button>
        
        <div class="offline-features">
            <h3>Disponible sin conexión:</h3>
            <ul>
                <li>Ver tu balance de puntos guardado</li>
                <li>Explorar productos en caché</li>
                <li>Ver historial de órdenes</li>
                <li>Consultar favoritos guardados</li>
                <li>Las acciones se sincronizarán automáticamente</li>
            </ul>
        </div>
    </div>

    <script>
        // Check connection status
        function updateConnectionStatus() {
            const indicator = document.getElementById('statusIndicator');
            const text = document.getElementById('statusText');
            
            if (navigator.onLine) {
                indicator.classList.add('online');
                text.textContent = 'Conectado';
                
                // Auto-reload when connection is restored
                setTimeout(() => {
                    if (navigator.onLine) {
                        location.reload();
                    }
                }, 2000);
            } else {
                indicator.classList.remove('online');
                text.textContent = 'Sin conexión';
            }
        }
        
        // Listen for connection changes
        window.addEventListener('online', updateConnectionStatus);
        window.addEventListener('offline', updateConnectionStatus);
        
        // Initial check
        updateConnectionStatus();
        
        // Check connection periodically
        setInterval(updateConnectionStatus, 5000);
        
        function goToDashboard() {
            // Try to navigate to dashboard (will work if cached)
            window.location.href = '/dashboard';
        }
        
        // Service Worker registration check
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.ready.then(registration => {
                console.log('Service Worker is ready');
            });
        }
        
        // Show install prompt if available
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            deferredPrompt = e;
            
            // Show custom install button
            const installButton = document.createElement('button');
            installButton.textContent = 'Instalar App';
            installButton.className = 'retry-button';
            installButton.style.background = 'rgba(255,255,255,0.3)';
            installButton.style.color = 'white';
            installButton.onclick = () => {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the install prompt');
                    }
                    deferredPrompt = null;
                });
            };
            
            document.querySelector('.offline-container').appendChild(installButton);
        });
    </script>
</body>
</html>