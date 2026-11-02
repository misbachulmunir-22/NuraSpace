<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class BirthdayReminderNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public function __construct(
        public User $birthdayUser,
        public string $stage, // 'h_minus_7', 'h_minus_1', 'day_of'
        public int $age
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
            default => 'HARI INI! 🎂',
        };

        return [
            'type' => 'birthday_reminder',
            'title' => "Pengingat Ulang Tahun {$this->birthdayUser->name} 🎂",
            'message' => "Ulang tahun {$this->birthdayUser->name} yang ke-{$this->age} {$stageText}! Jangan lupa berikan doa dan ucapan penuh cinta.",
            'user_id' => $this->birthdayUser->id,
            'stage' => $this->stage,
            'age' => $this->age,
            'url' => route('events.index'),
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
