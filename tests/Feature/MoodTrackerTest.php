<?php

use App\Events\MoodUpdatedEvent;
use App\Models\Couple;
use App\Models\Mood;
use App\Models\User;
use Illuminate\Support\Facades\Event;
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
});

test('partner can log and update daily mood with real-time broadcast', function () {
    Event::fake([MoodUpdatedEvent::class]);

    $response = $this->actingAs($this->munir)->post('/moods', [
        'mood_type' => 'happy',
        'note' => 'Hari ini sangat bahagia bisa bersama kamu!',
    ]);

    $response->assertSessionHas('success');

    $mood = Mood::where('user_id', $this->munir->id)->first();

    expect($mood)->not->toBeNull();
    expect($mood->mood_type)->toBe('happy');
    expect($mood->note)->toBe('Hari ini sangat bahagia bisa bersama kamu!');
    expect($mood->emoji)->toBe('😊');

    Event::assertDispatched(MoodUpdatedEvent::class, function ($event) use ($mood) {
        return $event->mood->id === $mood->id;
    });

    // Updating mood on the same day updates the existing record
    $this->actingAs($this->munir)->post('/moods', [
        'mood_type' => 'needy',
        'note' => 'Sekarang lagi manja dan kangen banget.',
    ]);

    expect(Mood::where('user_id', $this->munir->id)->count())->toBe(1);
    $mood->refresh();
    expect($mood->mood_type)->toBe('needy');
    expect($mood->emoji)->toBe('🫂');
});

test('child user cannot access mood tracker', function () {
    $child = User::create([
        'couple_id' => $this->couple->id,
        'name' => 'Rayyan',
        'email' => 'rayyan@test.local',
        'password' => Hash::make('Password123!'),
        'role' => 'child',
        'must_change_password' => false,
        'is_active' => true,
    ]);

    $response = $this->actingAs($child)->post('/moods', [
        'mood_type' => 'happy',
    ]);

    $response->assertStatus(403);
});
