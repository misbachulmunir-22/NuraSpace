<?php

namespace App\Events;

use App\Models\Mood;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MoodUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Mood $mood)
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
            new PrivateChannel('couple.' . $this->mood->couple_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'mood.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'mood_id' => $this->mood->id,
            'user_id' => $this->mood->user_id,
            'user_name' => $this->mood->user->name,
            'mood_type' => $this->mood->mood_type,
            'emoji' => $this->mood->emoji,
            'label' => $this->mood->label,
            'color' => $this->mood->color,
            'note' => $this->mood->note,
            'logged_date' => $this->mood->logged_date->format('Y-m-d'),
        ];
    }
}
