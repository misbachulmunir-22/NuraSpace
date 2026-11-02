<?php

use App\Models\Countdown;
use App\Models\Couple;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->couple = Couple::create(['name' => 'Munir & Syahra Space']);

    $this->munir = User::create([
        'couple_id' => $this->couple->id,
        'name' => 'Misbachul Munir',
        'email' => 'munir@test.local',
        'password' => Hash::make('Password123!'),
        'role' => 'partner',
        'must_change_password' => false,
        'is_active' => true,
    ]);

    $this->syahra = User::create([
        'couple_id' => $this->couple->id,
        'name' => 'Syahra Alfi Nur Arintia',
        'email' => 'syahra@test.local',
        'password' => Hash::make('Password123!'),
        'role' => 'partner',
        'must_change_password' => false,
        'is_active' => true,
    ]);

    $this->child = User::create([
        'couple_id' => $this->couple->id,
        'name' => 'Rayyan',
        'email' => 'rayyan@test.local',
        'password' => Hash::make('Password123!'),
        'role' => 'child',
        'must_change_password' => false,
        'is_active' => true,
    ]);
});

test('both partner and child can view countdowns', function () {
    Countdown::create([
        'couple_id' => $this->couple->id,
        'created_by_user_id' => $this->munir->id,
        'title' => 'Liburan ke Jepang',
        'target_date' => now()->addDays(45),
        'icon_emoji' => '✈️',
        'is_pinned' => true,
    ]);

    $partnerResponse = $this->actingAs($this->munir)->get('/countdowns');
    $partnerResponse->assertOk();
    $partnerResponse->assertSee('Liburan ke Jepang');

    $childResponse = $this->actingAs($this->child)->get('/countdowns');
    $childResponse->assertOk();
    $childResponse->assertSee('Liburan ke Jepang');
});

test('partner can create, pin, and delete countdown', function () {
    $response = $this->actingAs($this->syahra)->post('/countdowns', [
        'title' => 'Hari Wisuda Munir',
        'target_date' => now()->addDays(20)->format('Y-m-d H:i:s'),
        'icon_emoji' => '🎓',
        'is_pinned' => true,
    ]);

    $response->assertRedirect(route('countdowns.index'));

    $countdown = Countdown::where('title', 'Hari Wisuda Munir')->first();
    expect($countdown)->not->toBeNull();
    expect($countdown->is_pinned)->toBeTrue();
    expect($countdown->daysRemaining())->toBeGreaterThanOrEqual(19);

    // Toggle unpin
    $this->actingAs($this->munir)->put("/countdowns/{$countdown->id}/pin");
    $countdown->refresh();
    expect($countdown->is_pinned)->toBeFalse();

    // Delete
    $del = $this->actingAs($this->munir)->delete("/countdowns/{$countdown->id}");
    $del->assertRedirect(route('countdowns.index'));
    expect(Countdown::where('id', $countdown->id)->exists())->toBeFalse();
});
