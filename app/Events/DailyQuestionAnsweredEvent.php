<?php

namespace App\Events;

use App\Models\DailyQuestionCouple;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DailyQuestionAnsweredEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public DailyQuestionCouple $dailyQuestionCouple,
        public User $answeredBy,
        public bool $areBothAnswered
    ) {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('couple.' . $this->dailyQuestionCouple->couple_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'daily_question.answered';
    }

    public function broadcastWith(): array
    {
        return [
            'daily_question_couple_id' => $this->dailyQuestionCouple->id,
            'answered_by_user_id' => $this->answeredBy->id,
            'answered_by_name' => $this->answeredBy->name,
            'are_both_answered' => $this->areBothAnswered,
        ];
    }
}
