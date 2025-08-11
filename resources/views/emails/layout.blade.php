<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subject ?? 'UGo Rewards' }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8fafc;
        }
        
        .email-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .email-header {
            background: linear-gradient(135deg, #0ADD90 0%, #00AE6E 100%);
            padding: 30px 40px;
            text-align: center;
        }
        
        .email-header h1 {
            color: white;
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        
        .email-header p {
            color: rgba(255, 255, 255, 0.9);
            margin: 10px 0 0;
            font-size: 16px;
        }
        
        .email-content {
            padding: 40px;
        }
        
        .email-content h2 {
            color: #0ADD90;
            margin: 0 0 20px;
            font-size: 24px;
            font-weight: 600;
        }
        
        .email-content h3 {
            color: #00AE6E;
            margin: 25px 0 15px;
            font-size: 18px;
            font-weight: 600;
        }
        
        .email-content p {
            margin: 0 0 16px;
            font-size: 16px;
        }
        
        .highlight-box {
            background: #f0fdf4;
            border: 1px solid #0ADD90;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .info-box {
            background: #f8fafc;
            border-left: 4px solid #0ADD90;
            padding: 20px;
            margin: 20px 0;
        }
        
        .points-badge {
            display: inline-block;
            background: #0ADD90;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 18px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        .status-processing {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .status-completed {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .button {
            display: inline-block;
            background: #0ADD90;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            transition: background-color 0.3s;
        }
        
        .button:hover {
            background: #00AE6E;
        }
        
        .button-secondary {
            background: #6b7280;
        }
        
        .button-secondary:hover {
            background: #4b5563;
        }
        
        .order-details {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            margin: 20px 0;
        }
        
        .order-details table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .order-details th {
            background: #f9fafb;
            padding: 12px 16px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .order-details td {
            padding: 12px 16px;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .email-footer {
            background: #f9fafb;
            padding: 30px 40px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        
        .email-footer p {
            color: #6b7280;
            margin: 5px 0;
            font-size: 14px;
        }
        
        .social-links {
            margin: 20px 0 10px;
        }
        
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #0ADD90;
            text-decoration: none;
        }
        
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            
            .email-header, .email-content, .email-footer {
                padding: 20px;
            }
            
            .email-header h1 {
                font-size: 24px;
            }
            
            .email-content h2 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>{{ $title ?? 'UGo Rewards' }}</h1>
            @isset($subtitle)
                <p>{{ $subtitle }}</p>
            @endisset
        </div>
        
        <div class="email-content">
            @yield('content')
        </div>
        
        <div class="email-footer">
            <p><strong>UGo Rewards System</strong></p>
            <p>Sistema de recompensas corporativo</p>
            
            <div class="social-links">
                <a href="#">Portal Web</a> |
                <a href="#">Soporte</a> |
                <a href="#">Términos</a>
            </div>
            
            <p>Este correo fue enviado automáticamente. Por favor, no responder directamente.</p>
            <p>© {{ date('Y') }} UGo Rewards. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>