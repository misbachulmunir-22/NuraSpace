<?php

namespace Tests\Feature;

use App\Models\Couple;
use App\Models\Countdown;
use App\Models\Event;
use App\Models\Mood;
use App\Models\SavingsGoal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
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

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_user_with_unmodified_initial_password_is_redirected_to_change_password(): void
    {
        $userWithMustChange = User::factory()->create([
            'couple_id' => $this->couple->id,
            'must_change_password' => true,
        ]);

        $response = $this->actingAs($userWithMustChange)->get(route('dashboard'));

        $response->assertRedirect(route('profile.edit'));
    }

    public function test_partner_dashboard_shows_partner_widgets(): void
    {
        $today = Carbon::now('Asia/Jakarta')->toDateString();

        Mood::create([
            'couple_id' => $this->couple->id,
            'user_id' => $this->partner2->id,
            'mood_type' => 'happy',
            'logged_date' => $today,
            'note' => 'Hari ini sangat menyenangkan bersamamu!',
        ]);

        SavingsGoal::create([
            'couple_id' => $this->couple->id,
            'user_id' => $this->partner1->id,
            'name' => 'Rumah Masa Depan',
            'target_amount' => 100000000,
            'current_amount' => 20000000,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->partner1)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Misbachul Munir');
        $response->assertSee('Mood Pasangan Hari Ini');
        $response->assertSee('Hari ini sangat menyenangkan bersamamu!');
        $response->assertSee('Kirim Sentuhan Manis');
        $response->assertSee('Tabungan Bersama');
        $response->assertSee('Rumah Masa Depan');
    }

    public function test_child_dashboard_shows_family_safe_widgets_and_hides_private_partner_widgets(): void
    {
        Event::create([
            'couple_id' => $this->couple->id,
            'created_by_user_id' => $this->partner1->id,
            'title' => 'Piknik Kebun Raya',
            'start_at' => now()->addDays(2),
            'end_at' => now()->addDays(2)->addHours(3),
            'is_family_event' => true,
        ]);

        $response = $this->actingAs($this->child)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Ruang Keluarga NuraSpace');
        $response->assertSee('Peluk Orang Tua');
        $response->assertSee('Piknik Kebun Raya');

        // Private widgets must NOT be visible to child:
        $response->assertDontSee('Mood Pasangan Hari Ini');
        $response->assertDontSee('Tabungan Bersama');
        $response->assertDontSee('Pertanyaan Harian Pasangan');
    }
}
