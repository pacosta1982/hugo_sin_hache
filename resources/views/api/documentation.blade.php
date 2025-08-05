<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UGo API Documentation</title>
    
    
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist@4.15.5/swagger-ui.css" />
    <style>
        html {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }
        *, *:before, *:after {
            box-sizing: inherit;
        }
        body {
            margin:0;
            background: #fafafa;
        }
        .swagger-ui .topbar {
            background-color: #4F46E5;
        }
        .swagger-ui .topbar .download-url-wrapper {
            display: none;
        }
        .custom-header {
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
            color: white;
            padding: 2rem;
            text-align: center;
            margin-bottom: 0;
        }
        .custom-header h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 600;
        }
        .custom-header p {
            margin: 0.5rem 0 0 0;
            font-size: 1.1rem;
            opacity: 0.9;
        }
        .api-info {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 1.5rem;
        }
        .api-info-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 2rem;
        }
        .info-card {
            text-align: center;
            padding: 1rem;
            border-radius: 8px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        .info-card h3 {
            margin: 0 0 0.5rem 0;
            color: #4F46E5;
            font-size: 1.1rem;
        }
        .info-card p {
            margin: 0;
            color: #64748b;
            font-size: 0.9rem;
        }
        .swagger-container {
            max-width: none;
        }
        @media (max-width: 768px) {
            .api-info-content {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            .custom-header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    
    <div class="custom-header">
        <h1>🚀 UGo API Documentation</h1>
        <p>Sistema de Puntos y Recompensas - Documentación de API RESTful</p>
    </div>

    
    <div class="api-info">
        <div class="api-info-content">
            <div class="info-card">
                <h3>🔐 Autenticación</h3>
                <p>Firebase JWT tokens en header Authorization: "Bearer {token}"</p>
            </div>
            <div class="info-card">
                <h3>⚡ Rate Limiting</h3>
                <p>Límites por endpoint: Login (5/min), Canjes (3/min), Búsquedas (30/min)</p>
            </div>
            <div class="info-card">
                <h3>📝 Formato</h3>
                <p>JSON request/response. Errores consistentes con códigos estándar HTTP</p>
            </div>
        </div>
    </div>

    
    <div id="swagger-ui" class="swagger-container"></div>

    
    <script src="https://unpkg.com/swagger-ui-dist@4.15.5/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@4.15.5/swagger-ui-standalone-preset.js"></script>
    <script>
        window.onload = function() {
            // Build API spec
            const spec = @json($apiSpec);
            
            // Initialize Swagger UI
            const ui = SwaggerUIBundle({
                spec: spec,
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout",
                defaultModelsExpandDepth: 1,
                defaultModelExpandDepth: 1,
                defaultModelRendering: 'example',
                displayOperationId: false,
                tryItOutEnabled: true,
                requestInterceptor: function(request) {
                    // Add Firebase token if available
                    const token = localStorage.getItem('firebase_token');
                    if (token) {
                        request.headers['Authorization'] = 'Bearer ' + token;
                    }
                    return request;
                },
                responseInterceptor: function(response) {
                    // Handle authentication errors
                    if (response.status === 401) {
                        console.warn('API: Authentication required or token expired');
                    }
                    return response;
                }
            });

            // Add custom styling
            setTimeout(function() {
                // Hide the top bar URL input
                const topbar = document.querySelector('.swagger-ui .topbar');
                if (topbar) {
                    topbar.style.display = 'none';
                }

                // Add custom info
                const infoSection = document.querySelector('.swagger-ui .information-container');
                if (infoSection) {
                    const customInfo = document.createElement('div');
                    customInfo.innerHTML = `
                        <div style="background: #f0f9ff; border: 1px solid #0284c7; border-radius: 6px; padding: 16px; margin: 20px 0;">
                            <h4 style="margin: 0 0 8px 0; color: #0284c7;">🔧 Configuración de Autenticación</h4>
                            <p style="margin: 0; font-size: 14px; color: #0369a1;">
                                Para probar los endpoints, primero inicia sesión en la aplicación. 
                                El token JWT se agregará automáticamente a las solicitudes.
                            </p>
                        </div>
                    `;
                    infoSection.appendChild(customInfo);
                }
            }, 1000);

            window.ui = ui;
        };
    </script>
</body>
</html>