<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class LowStockAlertMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Collection $lowStockProducts,
        public Collection $outOfStockProducts,
        public int $totalActiveProducts = 0,
        public int $lowStockCount = 0,
        public int $outOfStockCount = 0
    ) {}

    public function envelope(): Envelope
    {
        $urgency = $this->outOfStockProducts->count() > 0 ? '¡URGENTE! ' : '';
        
        return new Envelope(
            subject: "{$urgency}Alerta de Stock Bajo - UGo Rewards",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.low-stock-alert',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
