<?php

namespace Tests\Feature;

use App\Events\DailyQuestionAnsweredEvent;
use App\Models\Couple;
use App\Models\DailyQuestion;
use App\Models\DailyQuestionAnswer;
use App\Models\DailyQuestionCouple;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\DailyQuestionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class DailyQuestionTest extends TestCase
{
    use RefreshDatabase;

    protected Couple $couple;
    protected User $partner1;
    protected User $partner2;
    protected User $child;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DailyQuestionSeeder::class);

        $this->couple = Couple::factory()->create();

        $this->partner1 = User::factory()->create([
            'couple_id' => $this->couple->id,
            'name' => 'Misbachul Munir',
            'role' => 'partner',
            'must_change_password' => false,
        ]);

        $this->partner2 = User::factory()->create([
            'couple_id' => $this->couple->id,
            'name' => 'Syahra Alfi Nur Arintia',
            'role' => 'partner',
            'must_change_password' => false,
        ]);

        $this->child = User::factory()->create([
            'couple_id' => $this->couple->id,
            'name' => 'Anak Tersayang',
            'role' => 'child',
            'must_change_password' => false,
        ]);
    }

    public function test_child_cannot_access_daily_questions(): void
    {
        $response = $this->actingAs($this->child)->get(route('daily-question.index'));

        $response->assertStatus(403);
    }

    public function test_partner_can_access_daily_question_and_auto_assigns_today_question(): void
    {
        $response = $this->actingAs($this->partner1)->get(route('daily-question.index'));

        $response->assertOk();
        $response->assertSee('Pertanyaan Hari Ini');

        $exists = DailyQuestionCouple::where('couple_id', $this->couple->id)
            ->whereDate('served_date', Carbon::now('Asia/Jakarta')->toDateString())
            ->exists();

        $this->assertTrue($exists);
    }

    public function test_partner_can_submit_answer_and_dispatches_event(): void
    {
        Event::fake([DailyQuestionAnsweredEvent::class]);

        // Visit to auto-generate question
        $this->actingAs($this->partner1)->get(route('daily-question.index'));

        $coupleQuestion = DailyQuestionCouple::where('couple_id', $this->couple->id)->first();

        $response = $this->actingAs($this->partner1)->post(route('daily-question.answer'), [
            'daily_question_couple_id' => $coupleQuestion->id,
            'answer_text' => 'Hari saat kita pertama kali makan es krim bersama di sore yang hujan.',
        ]);

        $response->assertRedirect(route('daily-question.index'));
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('daily_question_answers', [
            'couple_id' => $this->couple->id,
            'daily_question_couple_id' => $coupleQuestion->id,
            'user_id' => $this->partner1->id,
            'answer_text' => 'Hari saat kita pertama kali makan es krim bersama di sore yang hujan.',
        ]);

        Event::assertDispatched(DailyQuestionAnsweredEvent::class, function ($event) use ($coupleQuestion) {
            return $event->dailyQuestionCouple->id === $coupleQuestion->id &&
                $event->answeredBy->id === $this->partner1->id &&
                $event->areBothAnswered === false;
        });
    }

    public function test_partner_answer_remains_locked_until_both_partners_answer(): void
    {
        // Partner 2 submits answer first
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        $question = DailyQuestion::first();

        $coupleQuestion = DailyQuestionCouple::create([
            'couple_id' => $this->couple->id,
            'daily_question_id' => $question->id,
            'served_date' => $today,
        ]);

        DailyQuestionAnswer::create([
            'couple_id' => $this->couple->id,
            'daily_question_couple_id' => $coupleQuestion->id,
            'user_id' => $this->partner2->id,
            'answer_text' => 'Rahasia jawaban cinta Syahra yang belum boleh dibaca Munir!',
        ]);

        // Partner 1 visits page BEFORE answering
        $response = $this->actingAs($this->partner1)->get(route('daily-question.index'));

        $response->assertOk();
        // Partner 1 should NOT see partner 2's answer text!
        $response->assertDontSee('Rahasia jawaban cinta Syahra yang belum boleh dibaca Munir!');
        $response->assertSee('Tulis Jawabanmu Hari Ini');

        // Now Partner 1 submits answer
        $this->actingAs($this->partner1)->post(route('daily-question.answer'), [
            'daily_question_couple_id' => $coupleQuestion->id,
            'answer_text' => 'Jawaban cinta dari Munir untuk Syahra.',
        ]);

        // Now Partner 1 visits page AGAIN - both answered, both answers should now be visible!
        $responseAfter = $this->actingAs($this->partner1)->get(route('daily-question.index'));

        $responseAfter->assertOk();
        $responseAfter->assertSee('Rahasia jawaban cinta Syahra yang belum boleh dibaca Munir!');
        $responseAfter->assertSee('Jawaban cinta dari Munir untuk Syahra.');
        $responseAfter->assertSee('Keduanya Telah Menjawab!');
    }

    public function test_history_page_lists_past_questions(): void
    {
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        $question = DailyQuestion::first();

        DailyQuestionCouple::create([
            'couple_id' => $this->couple->id,
            'daily_question_id' => $question->id,
            'served_date' => $today,
        ]);

        $response = $this->actingAs($this->partner1)->get(route('daily-question.history'));

        $response->assertOk();
        $response->assertSee($question->question_text);
    }
}
