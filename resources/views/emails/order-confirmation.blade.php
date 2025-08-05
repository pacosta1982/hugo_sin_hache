<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmación de Canje - UGo</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #3b82f6; color: white; padding: 20px; text-align: center; }
        .content { padding: 30px 20px; }
        .order-details { background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
        .status { display: inline-block; padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .status-pending { background: #fef3c7; color: #92400e; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>¡Canje Confirmado!</h1>
            <p>Tu producto ha sido canjeado exitosamente</p>
        </div>
        
        <div class="content">
            <p>Hola <strong>{{ $employee->nombre }}</strong>,</p>
            
            <p>Te confirmamos que tu canje ha sido procesado exitosamente. A continuación encontrarás los detalles de tu pedido:</p>
            
            <div class="order-details">
                <h3>Detalles del Pedido #{{ $order->id }}</h3>
                <p><strong>Producto:</strong> {{ $order->producto_nombre }}</p>
                <p><strong>Puntos utilizados:</strong> {{ number_format($order->puntos_utilizados) }}</p>
                <p><strong>Fecha del pedido:</strong> {{ $order->fecha->format('d/m/Y H:i') }}</p>
                <p><strong>Estado:</strong> <span class="status status-pending">{{ $order->estado }}</span></p>
                @if($order->observaciones)
                    <p><strong>Observaciones:</strong> {{ $order->observaciones }}</p>
                @endif
            </div>
            
            <p><strong>¿Qué sigue?</strong></p>
            <ul>
                <li>Tu pedido está siendo procesado por nuestro equipo</li>
                <li>Te notificaremos cuando tu pedido esté listo</li>
                <li>Puedes verificar el estado de tu pedido en cualquier momento desde tu dashboard</li>
            </ul>
            
            @if($product && $product->terminos_condiciones)
                <div style="border-top: 1px solid #e5e7eb; padding-top: 20px; margin-top: 20px;">
                    <h4>Términos y Condiciones:</h4>
                    <p style="font-size: 14px; color: #666;">{{ $product->terminos_condiciones }}</p>
                </div>
            @endif
        </div>
        
        <div class="footer">
            <p>Gracias por usar el sistema UGo de puntos</p>
            <p>Este es un correo automático, por favor no responder</p>
        </div>
    </div>
</body>
</html>