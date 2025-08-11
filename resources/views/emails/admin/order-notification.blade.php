@extends('emails.layout', [
    'title' => 'Nueva Orden Recibida',
    'subtitle' => 'Acción requerida del administrador'
])

@section('content')
    <h2>Nueva Orden para Procesar</h2>
    
    <p>Se ha recibido una nueva orden en el sistema UGo Rewards que requiere tu atención.</p>
    
    <div class="highlight-box">
        <h3>Detalles de la Orden</h3>
        <div class="order-details">
            <table>
                <tr>
                    <th>Número de Orden</th>
                    <td><strong>#{{ $order->id }}</strong></td>
                </tr>
                <tr>
                    <th>Empleado</th>
                    <td>
                        <strong>{{ $order->employee->nombre }}</strong><br>
                        <span style="color: #6b7280;">{{ $order->employee->email }}</span>
                    </td>
                </tr>
                <tr>
                    <th>Producto</th>
                    <td>
                        <strong>{{ $order->product->nombre }}</strong>
                        @if($order->product->categoria)
                            <br><span style="color: #6b7280;">Categoría: {{ $order->product->categoria }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Puntos Utilizados</th>
                    <td><span class="points-badge">{{ number_format($order->puntos_utilizados) }} puntos</span></td>
                </tr>
                <tr>
                    <th>Estado Actual</th>
                    <td><span class="status-badge status-{{ $order->estado }}">{{ ucfirst($order->estado) }}</span></td>
                </tr>
                <tr>
                    <th>Fecha de Creación</th>
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
        <h3>Información del Empleado</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">Puntos Restantes</td>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; text-align: right;">
                    <span class="points-badge" style="font-size: 14px;">{{ number_format($order->employee->puntos_totales) }}</span>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">Total Canjeado</td>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; text-align: right;">
                    {{ number_format($order->employee->puntos_canjeados) }} puntos
                </td>
            </tr>
            <tr>
                <td style="padding: 8px 0;">Órdenes Anteriores</td>
                <td style="padding: 8px 0; text-align: right;">
                    {{ $order->employee->orders->count() - 1 }} órdenes
                </td>
            </tr>
        </table>
    </div>
    
    @if($order->product->stock !== -1)
    <div class="info-box">
        <h3>Estado del Inventario</h3>
        <p>
            <strong>Stock disponible:</strong> 
            @if($order->product->stock > 0)
                <span style="color: #059669;">{{ $order->product->stock }} unidades</span>
                @if($order->product->stock <= $order->product->stock_minimo)
                    <span style="color: #f59e0b; margin-left: 10px;">⚠️ Stock bajo</span>
                @endif
            @else
                <span style="color: #ef4444;">Agotado</span>
            @endif
        </p>
    </div>
    @endif
    
    <div class="highlight-box">
        <h3>Acciones Requeridas</h3>
        <ul style="margin: 15px 0; padding-left: 20px;">
            <li><strong>Revisar la orden:</strong> Verificar que todos los datos sean correctos</li>
            @if($order->product->integra_jira)
                <li><strong>Crear ticket Jira:</strong> Generar ticket automático para seguimiento</li>
            @endif
            <li><strong>Procesar orden:</strong> Cambiar estado a "processing" una vez iniciado</li>
            <li><strong>Confirmar entrega:</strong> Marcar como "completed" cuando se entregue</li>
            @if($order->product->envia_email)
                <li><strong>Comunicación:</strong> Mantener informado al empleado sobre el progreso</li>
            @endif
        </ul>
    </div>
    
    @if($order->product->terminos_condiciones)
    <div class="info-box">
        <h3>Términos y Condiciones del Producto</h3>
        <p>{{ $order->product->terminos_condiciones }}</p>
    </div>
    @endif
    
    <p style="text-align: center; margin-top: 30px;">
        <a href="{{ config('app.url') }}/admin/orders/{{ $order->id }}" class="button">Procesar Orden</a>
        <a href="{{ config('app.url') }}/admin/orders" class="button button-secondary">Ver Todas las Órdenes</a>
    </p>
    
    <p><strong>Tiempo de respuesta esperado:</strong> Las órdenes deben ser procesadas dentro de las próximas 24 horas para mantener la satisfacción del empleado.</p>
    
    <p style="color: #6b7280; font-size: 14px; margin-top: 20px;">
        <strong>ID de transacción:</strong> {{ $order->id }}<br>
        <strong>Empleado ID:</strong> {{ $order->employee->id_empleado }}
    </p>
@endsection