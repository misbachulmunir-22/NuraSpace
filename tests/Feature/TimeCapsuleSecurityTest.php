<?php

use App\Models\Couple;
use App\Models\TimeCapsule;
use App\Models\User;
use App\Notifications\TimeCapsuleOpenedNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

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

test('locked time capsule never reveals content to client before unlock_at', function () {
    $futureSecretContent = 'Surat cinta rahasia yang terkunci sangat rapat hingga tahun depan!';

    $capsule = TimeCapsule::create([
        'couple_id' => $this->couple->id,
        'sender_id' => $this->munir->id,
        'recipient_id' => $this->syahra->id,
        'title' => 'Surat Terkunci untuk Syahra',
        'content' => $futureSecretContent,
        'unlock_at' => now()->addDays(30),
        'is_notified' => false,
    ]);

    expect($capsule->isUnlocked())->toBeFalse();
    expect($capsule->getSafeContent())->toBeNull();

    // Syahra visits the time capsule show page
    $response = $this->actingAs($this->syahra)->get("/time-capsules/{$capsule->id}");
    $response->assertOk();

    // Verify raw content is STRICTLY NOT in the rendered HTML
    $response->assertDontSee($futureSecretContent);
    $response->assertSee('Surat Ini Sedang Tersegel Rapi');
});

test('unlocked time capsule reveals content when unlock_at has passed', function () {
    $unlockedContent = 'Selamat ulang tahun sayang! Ini adalah isi surat yang terbuka.';

    $capsule = TimeCapsule::create([
        'couple_id' => $this->couple->id,
        'sender_id' => $this->munir->id,
        'recipient_id' => $this->syahra->id,
        'title' => 'Surat yang Sudah Terbuka',
        'content' => $unlockedContent,
        'unlock_at' => now()->subMinute(), // in the past
        'is_notified' => false,
    ]);

    expect($capsule->isUnlocked())->toBeTrue();
    expect($capsule->getSafeContent())->toBe($unlockedContent);

    // Syahra visits the unlocked time capsule
    $response = $this->actingAs($this->syahra)->get("/time-capsules/{$capsule->id}");
    $response->assertOk();
    $response->assertSee($unlockedContent);

    $capsule->refresh();
    expect($capsule->opened_at)->not->toBeNull();
});

test('sender cannot modify unlock_at once sealed', function () {
    $capsule = TimeCapsule::create([
        'couple_id' => $this->couple->id,
        'sender_id' => $this->munir->id,
        'recipient_id' => $this->syahra->id,
        'title' => 'Surat Paten',
        'content' => 'Isi surat...',
        'unlock_at' => now()->addDays(10),
        'is_notified' => false,
    ]);

    // Attempting to change unlock_at must throw RuntimeException
    expect(fn () => $capsule->update(['unlock_at' => now()->addDays(5)]))
        ->toThrow(RuntimeException::class, 'Waktu buka surat masa depan (unlock_at) tidak dapat diubah setelah disegel.');
});

test('scheduler command checks unlocked time capsules and notifies recipient', function () {
    Notification::fake();

    $capsule = TimeCapsule::create([
        'couple_id' => $this->couple->id,
        'sender_id' => $this->munir->id,
        'recipient_id' => $this->syahra->id,
        'title' => 'Surat Hari Ini',
        'content' => 'Buka sekarang!',
        'unlock_at' => now()->subMinute(),
        'is_notified' => false,
    ]);

    $this->artisan('nuraspace:check-time-capsules')
        ->expectsOutputToContain('Menemukan 1 surat masa depan yang siap dibuka.')
        ->assertSuccessful();

    $capsule->refresh();
    expect($capsule->is_notified)->toBeTrue();

    Notification::assertSentTo($this->syahra, TimeCapsuleOpenedNotification::class);
});

test('child user cannot access time capsules', function () {
    $child = User::create([
        'couple_id' => $this->couple->id,
        'name' => 'Rayyan',
        'email' => 'rayyan@test.local',
        'password' => Hash::make('Password123!'),
        'role' => 'child',
        'must_change_password' => false,
        'is_active' => true,
    ]);

    $response = $this->actingAs($child)->get('/time-capsules');
    $response->assertStatus(403);
});
