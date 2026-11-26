<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class ReservationUpdated extends Notification
{
    public function __construct(
        public string $title,
        public string $body,
        public ?int $reservationId = null,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'reservation_id' => $this->reservationId,
        ];
    }
}
