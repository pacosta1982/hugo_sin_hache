@extends('emails.layout', [
    'title' => '¡Puntos Otorgados!',
    'subtitle' => 'Has recibido nuevos puntos en tu cuenta'
])

@section('content')
    <h2>¡Felicidades {{ $employee->nombre }}!</h2>
    
    <p>Te informamos que has recibido una nueva asignación de puntos en tu cuenta UGo Rewards.</p>
    
    <div class="highlight-box" style="text-align: center;">
        <h3>Puntos Recibidos</h3>
        <div style="margin: 20px 0;">
            <span class="points-badge" style="font-size: 24px; padding: 15px 30px;">
                +{{ number_format($points) }} puntos
            </span>
        </div>
        
        @if($description)
            <p><strong>Motivo:</strong> {{ $description }}</p>
        @endif
        
        @if($awardedBy)
            <p><strong>Otorgado por:</strong> {{ $awardedBy }}</p>
        @endif
    </div>
    
    <div class="info-box">
        <h3>Tu Balance Actualizado</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;"><strong>Puntos Disponibles</strong></td>
                <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; text-align: right;">
                    <span class="points-badge">{{ number_format($employee->puntos_totales) }}</span>
                </td>
            </tr>
            <tr>
                <td style="padding: 10px 0; color: #6b7280;">Total Ganado</td>
                <td style="padding: 10px 0; color: #6b7280; text-align: right;">
                    {{ number_format($employee->puntos_totales + $employee->puntos_canjeados) }} puntos
                </td>
            </tr>
            <tr>
                <td style="padding: 10px 0; color: #6b7280;">Total Canjeado</td>
                <td style="padding: 10px 0; color: #6b7280; text-align: right;">
                    {{ number_format($employee->puntos_canjeados) }} puntos
                </td>
            </tr>
        </table>
    </div>
    
    <div class="highlight-box">
        <h3>¿Qué puedes hacer ahora?</h3>
        <p>Con tus nuevos puntos, puedes explorar nuestro catálogo de productos y canjear increíbles recompensas:</p>
        <ul style="margin: 15px 0; padding-left: 20px;">
            <li>Productos tecnológicos y gadgets</li>
            <li>Experiencias y actividades</li>
            <li>Vales de regalo y descuentos</li>
            <li>Beneficios corporativos exclusivos</li>
        </ul>
    </div>
    
    @if(isset($recommendations) && $recommendations->count() > 0)
        <div class="info-box">
            <h3>Productos Recomendados para Ti</h3>
            <p>Basado en tu perfil, estos productos podrían interesarte:</p>
            
            @foreach($recommendations->take(3) as $product)
                <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 15px; margin: 10px 0;">
                    <h4 style="margin: 0 0 8px; color: #0ADD90;">{{ $product->nombre }}</h4>
                    <p style="margin: 0 0 8px; font-size: 14px; color: #6b7280;">{{ Str::limit($product->descripcion, 100) }}</p>
                    <p style="margin: 0; font-weight: 600;">
                        <span class="points-badge" style="font-size: 14px; padding: 4px 8px;">{{ number_format($product->puntos_requeridos) }} pts</span>
                        @if($employee->puntos_totales >= $product->puntos_requeridos)
                            <span style="color: #059669; font-size: 12px; margin-left: 10px;">✅ Puedes canjearlo</span>
                        @endif
                    </p>
                </div>
            @endforeach
        </div>
    @endif
    
    <p style="text-align: center; margin-top: 30px;">
        <a href="{{ config('app.url') }}/products" class="button">Explorar Catálogo</a>
        <a href="{{ config('app.url') }}/dashboard" class="button button-secondary">Ver Dashboard</a>
    </p>
    
    <p><strong>¡Sigue así!</strong> Continúa participando en actividades corporativas para ganar más puntos y acceder a mejores recompensas.</p>
@endsection