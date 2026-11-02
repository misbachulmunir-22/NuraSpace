<?php

namespace App\Notifications;

use App\Models\Couple;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class AnniversaryReminderNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public function __construct(
        public Couple $couple,
        public string $stage, // 'h_minus_7', 'h_minus_1', 'day_of'
        public int $years
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        $stageText = match ($this->stage) {
            'h_minus_7' => '7 hari lagi',
            'h_minus_1' => 'Besok!',
            default => 'HARI INI! 🎉',
        };

        return [
            'type' => 'anniversary_reminder',
            'title' => "Pengingat Anniversary {$this->years} Tahun 💍",
            'message' => "Momen anniversary kalian ({$this->couple->name}) {$stageText}! Jangan lupa siapkan momen berharga bersama.",
            'stage' => $this->stage,
            'years' => $this->years,
            'url' => route('events.index'),
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
