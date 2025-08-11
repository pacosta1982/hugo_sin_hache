@extends('emails.layout', [
    'title' => 'Pedido Confirmado',
    'subtitle' => 'Tu canje ha sido procesado exitosamente'
])

@section('content')
    <h2>¡Felicidades, {{ $employee->nombre }}!</h2>
    
    <p>Tu pedido ha sido confirmado y está siendo procesado. A continuación encontrarás los detalles:</p>
    
    <div class="highlight-box">
        <h3>Detalles del Pedido</h3>
        <div class="order-details">
            <table>
                <tr>
                    <th>Número de Pedido</th>
                    <td><strong>#{{ $order->id }}</strong></td>
                </tr>
                <tr>
                    <th>Producto</th>
                    <td>{{ $order->product->nombre }}</td>
                </tr>
                <tr>
                    <th>Puntos Utilizados</th>
                    <td><span class="points-badge">{{ number_format($order->puntos_utilizados) }} puntos</span></td>
                </tr>
                <tr>
                    <th>Estado</th>
                    <td><span class="status-badge status-{{ $order->estado }}">{{ ucfirst($order->estado) }}</span></td>
                </tr>
                <tr>
                    <th>Fecha del Pedido</th>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @if($order->observaciones)
                <tr>
                    <th>Observaciones</th>
                    <td>{{ $order->observaciones }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>
    
    @if($order->product->descripcion)
    <div class="info-box">
        <h3>Descripción del Producto</h3>
        <p>{{ $order->product->descripcion }}</p>
    </div>
    @endif
    
    <div class="info-box">
        <h3>Tu Balance Actual</h3>
        <p>Puntos disponibles: <span class="points-badge">{{ number_format($employee->puntos_totales) }}</span></p>
        <p>Total canjeado: {{ number_format($employee->puntos_canjeados) }} puntos</p>
    </div>
    
    <h3>¿Qué sigue?</h3>
    <p>Nuestro equipo procesará tu pedido y te mantendremos informado sobre cualquier actualización. Recibirás notificaciones por correo y en la plataforma.</p>
    
    @if($order->product->terminos_condiciones)
    <div class="info-box">
        <h3>Términos y Condiciones</h3>
        <p>{{ $order->product->terminos_condiciones }}</p>
    </div>
    @endif
    
    <p style="text-align: center; margin-top: 30px;">
        <a href="{{ config('app.url') }}/orders/{{ $order->id }}" class="button">Ver Detalles del Pedido</a>
        <a href="{{ config('app.url') }}/products" class="button button-secondary">Explorar Más Productos</a>
    </p>
    
    <p><strong>¿Necesitas ayuda?</strong> Contáctanos si tienes alguna pregunta sobre tu pedido.</p>
@endsection