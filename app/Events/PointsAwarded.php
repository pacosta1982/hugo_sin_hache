<?php

namespace App\Events;

use App\Models\Employee;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PointsAwarded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Employee $employee,
        public int $points,
        public string $description,
        public ?string $awardedBy = null
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("employee.{$this->employee->id_empleado}"),
            new PrivateChannel('admin.points'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'points.awarded';
    }

    public function broadcastWith(): array
    {
        return [
            'employee_id' => $this->employee->id_empleado,
            'employee_name' => $this->employee->nombre,
            'points_awarded' => $this->points,
            'new_balance' => $this->employee->puntos_totales,
            'description' => $this->description,
            'awarded_by' => $this->awardedBy,
            'timestamp' => now()->toISOString(),
        ];
    }
}
