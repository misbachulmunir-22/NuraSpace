<?php

namespace App\Notifications;

use App\Models\ChildAccountRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ChildAccountApprovalNeededNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public function __construct(public ChildAccountRequest $childRequest)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'child_approval_needed',
            'title' => 'Permintaan Akun Anak Baru',
            'message' => "{$this->childRequest->requester->name} mengajukan pembuatan akun anak untuk {$this->childRequest->name}. Menunggu persetujuan Anda.",
            'request_id' => $this->childRequest->id,
            'child_name' => $this->childRequest->name,
            'url' => route('family.index'),
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
