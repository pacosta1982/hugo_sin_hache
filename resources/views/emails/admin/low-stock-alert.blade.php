@extends('emails.layout', [
    'title' => 'Alerta de Stock Bajo',
    'subtitle' => 'Atención requerida en inventario'
])

@section('content')
    <h2>Alerta de Inventario</h2>
    
    <p>Se ha detectado que uno o más productos requieren atención en el inventario del sistema UGo Rewards.</p>
    
    @if(isset($lowStockProducts) && $lowStockProducts->count() > 0)
        <div class="highlight-box">
            <h3>Productos con Stock Bajo</h3>
            <div class="order-details">
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Stock Actual</th>
                            <th>Stock Mínimo</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lowStockProducts as $product)
                        <tr>
                            <td>
                                <strong>{{ $product->nombre }}</strong>
                                @if($product->categoria)
                                    <br><small style="color: #6b7280;">{{ $product->categoria }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="points-badge" style="background: #f59e0b; font-size: 14px;">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td>{{ $product->stock_minimo }}</td>
                            <td>
                                @if($product->stock === 0)
                                    <span class="status-badge" style="background: #fee2e2; color: #991b1b;">Agotado</span>
                                @else
                                    <span class="status-badge" style="background: #fef3c7; color: #92400e;">Stock Bajo</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    
    @if(isset($outOfStockProducts) && $outOfStockProducts->count() > 0)
        <div class="info-box" style="border-left-color: #ef4444;">
            <h3>🚨 Productos Agotados</h3>
            <p><strong>Atención Urgente:</strong> Los siguientes productos están completamente agotados:</p>
            <ul style="margin: 15px 0; padding-left: 20px;">
                @foreach($outOfStockProducts as $product)
                    <li><strong>{{ $product->nombre }}</strong> - Requiere restock inmediato</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div class="highlight-box">
        <h3>Acciones Recomendadas</h3>
        <ul style="margin: 15px 0; padding-left: 20px;">
            <li><strong>Revisar inventario:</strong> Verificar el stock físico de los productos afectados</li>
            <li><strong>Actualizar stock:</strong> Incrementar las cantidades en el sistema si hay productos disponibles</li>
            <li><strong>Gestionar pedidos:</strong> Contactar proveedores para restock de productos agotados</li>
            <li><strong>Comunicar:</strong> Informar a los empleados sobre productos temporalmente no disponibles</li>
        </ul>
    </div>
    
    <div class="info-box">
        <h3>Estadísticas de Inventario</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">Total de Productos Activos</td>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; text-align: right; font-weight: 600;">
                    {{ $totalActiveProducts ?? 'N/A' }}
                </td>
            </tr>
            <tr>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">Productos con Stock Bajo</td>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; text-align: right; color: #f59e0b; font-weight: 600;">
                    {{ $lowStockCount ?? 0 }}
                </td>
            </tr>
            <tr>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">Productos Agotados</td>
                <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; text-align: right; color: #ef4444; font-weight: 600;">
                    {{ $outOfStockCount ?? 0 }}
                </td>
            </tr>
            <tr>
                <td style="padding: 8px 0;">Productos con Stock Adecuado</td>
                <td style="padding: 8px 0; text-align: right; color: #059669; font-weight: 600;">
                    {{ ($totalActiveProducts ?? 0) - ($lowStockCount ?? 0) - ($outOfStockCount ?? 0) }}
                </td>
            </tr>
        </table>
    </div>
    
    <p style="text-align: center; margin-top: 30px;">
        <a href="{{ config('app.url') }}/admin/products" class="button">Gestionar Inventario</a>
        <a href="{{ config('app.url') }}/admin/reports" class="button button-secondary">Ver Reportes</a>
    </p>
    
    <p><strong>Nota:</strong> Esta alerta se ha enviado automáticamente basándose en los niveles de stock configurados. Para modificar estos parámetros, accede al panel de administración.</p>
    
    <p style="color: #6b7280; font-size: 14px; margin-top: 20px;">
        <strong>Fecha del reporte:</strong> {{ now()->format('d/m/Y H:i') }}<br>
        <strong>Próxima verificación:</strong> {{ now()->addHours(24)->format('d/m/Y H:i') }}
    </p>
@endsection