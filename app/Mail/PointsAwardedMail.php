<?php

namespace App\Mail;

use App\Models\Employee;
use App\Services\ProductRecommendationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class PointsAwardedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Employee $employee,
        public int $points,
        public string $description,
        public ?string $awardedBy = null,
        public ?Collection $recommendations = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "¡Has recibido {$this->points} puntos! - UGo Rewards",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.points.awarded',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
