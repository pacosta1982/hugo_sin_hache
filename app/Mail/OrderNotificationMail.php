<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Nueva Orden #{$this->order->id}: {$this->order->product->nombre} - UGo Rewards",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.order-notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
