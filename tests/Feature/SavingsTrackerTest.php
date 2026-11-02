<?php

namespace Tests\Feature;

use App\Models\Couple;
use App\Models\SavingsGoal;
use App\Models\SavingsTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SavingsTrackerTest extends TestCase
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

    public function test_child_cannot_access_savings_tracker(): void
    {
        $response = $this->actingAs($this->child)->get(route('savings.index'));

        $response->assertStatus(403);
    }

    public function test_partner_can_create_savings_goal(): void
    {
        $response = $this->actingAs($this->partner1)->post(route('savings.goals.store'), [
            'name' => 'Dana Rumah Impian',
            'target_amount' => 100000000,
            'target_date' => '2028-12-31',
            'description' => 'Target tabungan DP rumah masa depan berdua.',
            'icon' => '🏠',
            'color' => 'blue',
        ]);

        $response->assertRedirect(route('savings.index'));
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('savings_goals', [
            'couple_id' => $this->couple->id,
            'user_id' => $this->partner1->id,
            'name' => 'Dana Rumah Impian',
            'target_amount' => 100000000,
            'current_amount' => 0,
            'status' => 'active',
        ]);
    }

    public function test_partner_can_deposit_funds_and_balance_auto_updates(): void
    {
        $goal = SavingsGoal::create([
            'couple_id' => $this->couple->id,
            'user_id' => $this->partner1->id,
            'name' => 'Liburan ke Swiss',
            'target_amount' => 50000000,
            'current_amount' => 0,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->partner2)->post(route('savings.transactions.store', $goal), [
            'amount' => 10000000,
            'transaction_date' => '2026-05-01',
            'notes' => 'Setoran bonus tahunan',
        ]);

        $response->assertRedirect(route('savings.show', $goal));
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('savings_transactions', [
            'savings_goal_id' => $goal->id,
            'couple_id' => $this->couple->id,
            'user_id' => $this->partner2->id,
            'amount' => 10000000,
        ]);

        $goal->refresh();
        $this->assertEquals(10000000, $goal->current_amount);
        $this->assertEquals(20.0, $goal->progress_percentage);
        $this->assertEquals(40000000, $goal->remaining_amount);
    }

    public function test_savings_goal_marks_achieved_when_balance_meets_target(): void
    {
        $goal = SavingsGoal::create([
            'couple_id' => $this->couple->id,
            'user_id' => $this->partner1->id,
            'name' => 'Laptop Baru Syahra',
            'target_amount' => 15000000,
            'current_amount' => 0,
            'status' => 'active',
        ]);

        $this->actingAs($this->partner1)->post(route('savings.transactions.store', $goal), [
            'amount' => 15000000,
            'transaction_date' => '2026-06-01',
            'notes' => 'Lunas terkumpul',
        ]);

        $goal->refresh();
        $this->assertEquals('achieved', $goal->status);
        $this->assertNotNull($goal->achieved_at);
        $this->assertEquals(100.0, $goal->progress_percentage);
    }

    public function test_deleting_transaction_recalculates_balance(): void
    {
        $goal = SavingsGoal::create([
            'couple_id' => $this->couple->id,
            'user_id' => $this->partner1->id,
            'name' => 'Dana Darurat',
            'target_amount' => 20000000,
            'current_amount' => 0,
            'status' => 'active',
        ]);

        $trx1 = SavingsTransaction::create([
            'savings_goal_id' => $goal->id,
            'couple_id' => $this->couple->id,
            'user_id' => $this->partner1->id,
            'amount' => 5000000,
            'transaction_date' => '2026-01-01',
        ]);

        $trx2 = SavingsTransaction::create([
            'savings_goal_id' => $goal->id,
            'couple_id' => $this->couple->id,
            'user_id' => $this->partner1->id,
            'amount' => 3000000,
            'transaction_date' => '2026-02-01',
        ]);

        $goal->refresh();
        $this->assertEquals(8000000, $goal->current_amount);

        // Delete trx2
        $response = $this->actingAs($this->partner1)->delete(route('savings.transactions.destroy', $trx2));
        $response->assertRedirect(route('savings.show', $goal));

        $goal->refresh();
        $this->assertEquals(5000000, $goal->current_amount);
    }
}
