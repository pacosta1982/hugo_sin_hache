<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdateMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public Employee $employee
    ) {}

    public function envelope(): Envelope
    {
        $statusText = match($this->order->estado) {
            'pending' => 'Pendiente',
            'processing' => 'En Proceso',
            'completed' => 'Completado',
            'cancelled' => 'Cancelado',
            default => ucfirst($this->order->estado)
        };
        
        return new Envelope(
            subject: "Actualización de Pedido #{$this->order->id}: {$statusText} - UGo Rewards",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.status-update',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
