<?php

namespace App\Events;

use App\Models\Nudge;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NudgeSentEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Nudge $nudge)
    {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('couple.' . $this->nudge->couple_id),
            new PrivateChannel('user.' . $this->nudge->recipient_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'nudge.received';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->nudge->id,
            'nudge_type' => $this->nudge->nudge_type,
            'label' => $this->nudge->label,
            'verb' => $this->nudge->verb,
            'emoji' => $this->nudge->emoji,
            'animation' => $this->nudge->animation,
            'sender_id' => $this->nudge->sender_id,
            'sender_name' => $this->nudge->sender->name,
            'recipient_id' => $this->nudge->recipient_id,
            'recipient_name' => $this->nudge->recipient->name,
            'created_at' => $this->nudge->created_at->format('H:i'),
        ];
    }
}
