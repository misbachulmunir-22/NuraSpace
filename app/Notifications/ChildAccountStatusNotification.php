<?php

namespace App\Notifications;

use App\Models\ChildAccountRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ChildAccountStatusNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public function __construct(
        public ChildAccountRequest $childRequest,
        public string $status, // 'approved' or 'rejected'
        public string $actorName
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        $statusText = $this->status === 'approved' ? 'disetujui dan akun telah aktif' : 'ditolak';

        return [
            'type' => 'child_approval_result',
            'title' => 'Status Permintaan Akun Anak',
            'message' => "Permintaan akun anak untuk {$this->childRequest->name} telah {$statusText} oleh {$this->actorName}.",
            'request_id' => $this->childRequest->id,
            'status' => $this->status,
            'url' => route('family.index'),
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
