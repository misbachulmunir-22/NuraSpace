<?php

namespace App\Notifications;

use App\Models\TimeCapsule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class TimeCapsuleOpenedNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public function __construct(public TimeCapsule $timeCapsule)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'time_capsule_opened',
            'title' => 'Surat Masa Depan Terbuka! 💌',
            'message' => "Surat masa depan '{$this->timeCapsule->title}' dari {$this->timeCapsule->sender->name} kini telah terbuka dan dapat Anda baca.",
            'capsule_id' => $this->timeCapsule->id,
            'url' => route('time-capsules.show', $this->timeCapsule),
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
