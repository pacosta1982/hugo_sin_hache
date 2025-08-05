<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nuevo Pedido - UGo Admin</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #dc2626; color: white; padding: 20px; text-align: center; }
        .content { padding: 30px 20px; }
        .order-details { background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
        .alert { background: #fef2f2; border: 1px solid #fecaca; padding: 15px; border-radius: 4px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Nuevo Pedido Recibido</h1>
            <p>Se ha realizado un nuevo canje en el sistema</p>
        </div>
        
        <div class="content">
            <p>Se ha registrado un nuevo pedido en el sistema UGo que requiere tu atención.</p>
            
            <div class="order-details">
                <h3>Detalles del Pedido #{{ $order->id }}</h3>
                <p><strong>Empleado:</strong> {{ $order->empleado_nombre }} ({{ $order->empleado_id }})</p>
                <p><strong>Email:</strong> {{ $employee->email }}</p>
                <p><strong>Producto:</strong> {{ $order->producto_nombre }}</p>
                <p><strong>Puntos utilizados:</strong> {{ number_format($order->puntos_utilizados) }}</p>
                <p><strong>Fecha del pedido:</strong> {{ $order->fecha->format('d/m/Y H:i') }}</p>
                <p><strong>Estado actual:</strong> {{ $order->estado }}</p>
                @if($order->observaciones)
                    <p><strong>Observaciones del empleado:</strong> {{ $order->observaciones }}</p>
                @endif
            </div>
            
            @if($product)
                <div class="order-details">
                    <h4>Información del Producto</h4>
                    <p><strong>Categoría:</strong> {{ $product->categoria ?? 'No especificada' }}</p>
                    <p><strong>Descripción:</strong> {{ $product->descripcion ?? 'Sin descripción' }}</p>
                    @if($product->stock !== -1)
                        <p><strong>Stock restante:</strong> {{ $product->stock }}</p>
                    @endif
                    @if($product->integra_jira)
                        <div class="alert">
                            <strong>⚠️ Atención:</strong> Este producto requiere integración con Jira.
                        </div>
                    @endif
                </div>
            @endif
            
            <p><strong>Acciones requeridas:</strong></p>
            <ul>
                <li>Revisar y procesar el pedido</li>
                <li>Actualizar el estado según corresponda</li>
                <li>Contactar al empleado si es necesario</li>
            </ul>
            
            <p style="text-align: center; margin-top: 30px;">
                <a href="{{ config('app.url') }}/admin/pedidos" 
                   style="background: #3b82f6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">
                    Ver Pedido en Admin Panel
                </a>
            </p>
        </div>
        
        <div class="footer">
            <p>Sistema UGo - Notificación Automática para Administradores</p>
            <p>Este es un correo automático, por favor no responder</p>
        </div>
    </div>
</body>
</html>