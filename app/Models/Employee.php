<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employees';
    protected $primaryKey = 'id_empleado';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_empleado',
        'nombre',
        'email',
        'puntos_totales',
        'puntos_canjeados',
        'rol_usuario',
    ];

    protected $casts = [
        'puntos_totales' => 'integer',
        'puntos_canjeados' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'empleado_id', 'id_empleado');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class, 'empleado_id', 'id_empleado');
    }

    public function pointTransactions(): HasMany
    {
        return $this->hasMany(PointTransaction::class, 'empleado_id', 'id_empleado');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'empleado_id', 'id_empleado');
    }

    public function recentActivity(int $limit = 5)
    {
        return $this->orders()
            ->latest('created_at')
            ->limit($limit)
            ->get();
    }

    public function getPuntosDisponiblesAttribute(): int
    {
        return $this->puntos_totales;
    }

    public function getIsAdminAttribute(): bool
    {
        return $this->rol_usuario === 'Administrador';
    }

    public function hasEnoughPoints(int $required): bool
    {
        return $this->puntos_totales >= $required;
    }

    public function deductPoints(int $points, string $description = 'Product redemption', ?array $metadata = null): void
    {
        $this->update([
            'puntos_totales' => $this->puntos_totales - $points,
            'puntos_canjeados' => $this->puntos_canjeados + $points,
        ]);

        PointTransaction::recordTransaction(
            $this->id_empleado,
            'spent',
            $points,
            $description,
            null,
            $metadata
        );
    }

    public function awardPoints(int $points, string $description, ?string $adminId = null, ?array $metadata = null): void
    {
        $this->update([
            'puntos_totales' => $this->puntos_totales + $points,
        ]);

        PointTransaction::recordTransaction(
            $this->id_empleado,
            'earned',
            $points,
            $description,
            $adminId,
            $metadata
        );


        Notification::notifyPointsAwarded($this->id_empleado, $points, $description, $adminId);
        

        try {
            $emailService = app(\App\Services\EmailService::class);
            $emailService->sendPointsAwardedNotification($this, $points, $description, $adminId);
        } catch (\Exception $e) {
            \Log::warning('Failed to send points awarded email', [
                'employee_id' => $this->id_empleado,
                'error' => $e->getMessage()
            ]);
        }


        try {
            $realtimeService = app(\App\Services\RealtimeService::class);
            $realtimeService->broadcastPointsAwarded($this, $points, $description, $adminId);
        } catch (\Exception $e) {
            \Log::warning('Failed to broadcast points awarded', [
                'employee_id' => $this->id_empleado,
                'error' => $e->getMessage()
            ]);
        }
    }
}