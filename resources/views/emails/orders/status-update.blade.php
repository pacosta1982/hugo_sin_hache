@extends('emails.layout', [
    'title' => 'Actualización de Pedido',
    'subtitle' => 'El estado de tu pedido ha cambiado'
])

@section('content')
    <h2>Hola {{ $employee->nombre }},</h2>
    
    <p>Te informamos que el estado de tu pedido <strong>#{{ $order->id }}</strong> ha sido actualizado.</p>
    
    <div class="highlight-box">
        <h3>Estado Actualizado</h3>
        <p style="text-align: center; margin: 20px 0;">
            <span class="status-badge status-{{ $order->estado }}" style="font-size: 18px; padding: 10px 20px;">
                {{ $this->getStatusText($order->estado) }}
            </span>
        </p>
    </div>
    
    <div class="order-details">
        <table>
            <tr>
                <th>Producto</th>
                <td>{{ $order->product->nombre }}</td>
            </tr>
            <tr>
                <th>Puntos Utilizados</th>
                <td><span class="points-badge">{{ number_format($order->puntos_utilizados) }} puntos</span></td>
            </tr>
            <tr>
                <th>Fecha del Pedido</th>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <th>Última Actualización</th>
                <td>{{ $order->updated_at->format('d/m/Y H:i') }}</td>
            </tr>
        </table>
    </div>
    
    @if($order->estado === 'processing')
        <div class="info-box">
            <h3>En Proceso</h3>
            <p>¡Excelente! Tu pedido está siendo procesado por nuestro equipo. Te mantendremos informado sobre el progreso.</p>
        </div>
    @elseif($order->estado === 'completed')
        <div class="info-box">
            <h3>¡Pedido Completado!</h3>
            <p>Tu pedido ha sido completado exitosamente. ¡Disfruta tu recompensa!</p>
            
            @if($order->product->integra_jira)
                <p><strong>Nota:</strong> Se ha creado un ticket en nuestro sistema para el seguimiento de entrega.</p>
            @endif
        </div>
        
        <div class="highlight-box">
            <h3>¡Sigue Ganando Puntos!</h3>
            <p>Explora más productos increíbles en nuestro catálogo y continúa acumulando puntos.</p>
        </div>
    @elseif($order->estado === 'cancelled')
        <div class="info-box">
            <h3>Pedido Cancelado</h3>
            <p>Tu pedido ha sido cancelado. Los puntos utilizados han sido devueltos automáticamente a tu cuenta.</p>
            <p><strong>Puntos restaurados:</strong> <span class="points-badge">{{ number_format($order->puntos_utilizados) }}</span></p>
        </div>
        
        <div class="highlight-box">
            <h3>Balance Actualizado</h3>
            <p>Puntos disponibles: <span class="points-badge">{{ number_format($employee->puntos_totales) }}</span></p>
        </div>
    @endif
    
    <p style="text-align: center; margin-top: 30px;">
        <a href="{{ config('app.url') }}/orders/{{ $order->id }}" class="button">Ver Detalles Completos</a>
        @if($order->estado !== 'cancelled')
            <a href="{{ config('app.url') }}/dashboard" class="button button-secondary">Ir al Dashboard</a>
        @else
            <a href="{{ config('app.url') }}/products" class="button button-secondary">Explorar Productos</a>
        @endif
    </p>
    
    <p>Si tienes alguna pregunta sobre esta actualización, no dudes en contactarnos.</p>
@endsection

@php
function getStatusText($status) {
    return match($status) {
        'pending' => 'Pendiente',
        'processing' => 'En Proceso',
        'completed' => 'Completado',
        'cancelled' => 'Cancelado',
        default => ucfirst($status)
    };
}
@endphp