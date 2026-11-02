<?php

namespace Tests\Feature;

use App\Events\NudgeSentEvent;
use App\Models\Couple;
use App\Models\Nudge;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class NudgeTest extends TestCase
{
    use RefreshDatabase;

    protected Couple $couple;
    protected User $partner1;
    protected User $partner2;
    protected User $child;

    protected function setUp(): void
    {
        parent::setUp();

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

    public function test_partner_can_send_nudge_to_partner(): void
    {
        Event::fake([NudgeSentEvent::class]);

        $response = $this->actingAs($this->partner1)->post(route('nudges.store'), [
            'nudge_type' => 'kiss',
            'recipient_id' => $this->partner2->id,
        ]);

        $response->assertSessionHas('status');

        $this->assertDatabaseHas('nudges', [
            'couple_id' => $this->couple->id,
            'sender_id' => $this->partner1->id,
            'recipient_id' => $this->partner2->id,
            'nudge_type' => 'kiss',
        ]);

        Event::assertDispatched(NudgeSentEvent::class, function ($event) {
            return $event->nudge->nudge_type === 'kiss' &&
                $event->nudge->recipient_id === $this->partner2->id;
        });
    }

    public function test_partner_can_send_nudge_without_explicit_recipient_id(): void
    {
        Event::fake([NudgeSentEvent::class]);

        $response = $this->actingAs($this->partner1)->postJson(route('nudges.store'), [
            'nudge_type' => 'hug',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'nudge' => [
                'nudge_type' => 'hug',
                'recipient_name' => $this->partner2->name,
            ],
        ]);

        $this->assertDatabaseHas('nudges', [
            'sender_id' => $this->partner1->id,
            'recipient_id' => $this->partner2->id,
            'nudge_type' => 'hug',
        ]);
    }

    public function test_child_can_send_nudge_to_parent(): void
    {
        Event::fake([NudgeSentEvent::class]);

        $response = $this->actingAs($this->child)->post(route('nudges.store'), [
            'nudge_type' => 'hug',
            'recipient_id' => $this->partner1->id,
        ]);

        $response->assertSessionHas('status');

        $this->assertDatabaseHas('nudges', [
            'sender_id' => $this->child->id,
            'recipient_id' => $this->partner1->id,
            'nudge_type' => 'hug',
        ]);
    }

    public function test_invalid_nudge_type_is_rejected(): void
    {
        $response = $this->actingAs($this->partner1)->postJson(route('nudges.store'), [
            'nudge_type' => 'slap',
        ]);

        $response->assertStatus(422);
    }

    public function test_recipient_from_another_couple_is_rejected(): void
    {
        $otherCouple = Couple::factory()->create();
        $stranger = User::factory()->create([
            'couple_id' => $otherCouple->id,
            'role' => 'partner',
        ]);

        $response = $this->actingAs($this->partner1)->postJson(route('nudges.store'), [
            'nudge_type' => 'hug',
            'recipient_id' => $stranger->id,
        ]);

        $response->assertStatus(422);
    }

    public function test_recent_unread_nudges_can_be_retrieved(): void
    {
        Nudge::create([
            'couple_id' => $this->couple->id,
            'sender_id' => $this->partner2->id,
            'recipient_id' => $this->partner1->id,
            'nudge_type' => 'miss_you',
        ]);

        $response = $this->actingAs($this->partner1)->getJson(route('nudges.recent'));

        $response->assertOk();
        $response->assertJsonPath('count', 1);
        $response->assertJsonPath('nudges.0.nudge_type', 'miss_you');
    }

    public function test_nudge_can_be_marked_as_read(): void
    {
        $nudge = Nudge::create([
            'couple_id' => $this->couple->id,
            'sender_id' => $this->partner2->id,
            'recipient_id' => $this->partner1->id,
            'nudge_type' => 'hug',
        ]);

        $this->assertNull($nudge->read_at);

        $response = $this->actingAs($this->partner1)->postJson(route('nudges.read', $nudge));

        $response->assertOk();
        $this->assertNotNull($nudge->fresh()->read_at);
    }
}
