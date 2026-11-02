<?php

use App\Models\Couple;
use App\Models\Journal;
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
});

test('partner can write a private journal and view it', function () {
    $response = $this->actingAs($this->munir)->post('/journals', [
        'title' => 'Rencana Rahasia Kejutan Anniversary',
        'content' => 'Aku sedang menyiapkan kejutan cincin spesial untuk Syahra.',
        'visibility' => 'private',
        'mood_tag' => 'Rahasia Manis',
        'entry_date' => now()->toDateString(),
    ]);

    $response->assertRedirect(route('journals.index'));

    $journal = Journal::where('title', 'Rencana Rahasia Kejutan Anniversary')->first();
    expect($journal)->not->toBeNull();
    expect($journal->isPrivate())->toBeTrue();

    // Munir can view his own private journal
    $showResponse = $this->actingAs($this->munir)->get("/journals/{$journal->id}");
    $showResponse->assertOk();
    $showResponse->assertSee('Rencana Rahasia Kejutan Anniversary');
});

test('partner CANNOT view private journal belonging to the other partner', function () {
    $privateJournal = Journal::create([
        'couple_id' => $this->couple->id,
        'user_id' => $this->munir->id,
        'title' => 'Catatan Rahasia Munir',
        'content' => 'Isi hati yang sangat privat yang belum ingin diceritakan.',
        'visibility' => 'private',
        'entry_date' => now()->toDateString(),
    ]);

    // Syahra tries to access Munir's private journal -> 403 Forbidden!
    $forbiddenResponse = $this->actingAs($this->syahra)->get("/journals/{$privateJournal->id}");
    $forbiddenResponse->assertStatus(403);
});

test('private journal is filtered out from other partner index query', function () {
    Journal::create([
        'couple_id' => $this->couple->id,
        'user_id' => $this->munir->id,
        'title' => 'Curhatan Rahasia Munir 123',
        'content' => 'Rahasia...',
        'visibility' => 'private',
        'entry_date' => now()->toDateString(),
    ]);

    Journal::create([
        'couple_id' => $this->couple->id,
        'user_id' => $this->munir->id,
        'title' => 'Liburan Bersama ke Pantai',
        'content' => 'Sangat menyenangkan kemarin jalan-jalan bareng.',
        'visibility' => 'shared',
        'entry_date' => now()->toDateString(),
    ]);

    // Syahra accesses /journals
    $response = $this->actingAs($this->syahra)->get('/journals');
    $response->assertOk();

    // Syahra sees the shared journal
    $response->assertSee('Liburan Bersama ke Pantai');

    // Syahra NEVER sees the private journal title in HTML response
    $response->assertDontSee('Curhatan Rahasia Munir 123');
});

test('both partners can view shared journal', function () {
    $sharedJournal = Journal::create([
        'couple_id' => $this->couple->id,
        'user_id' => $this->munir->id,
        'title' => 'Impian Rumah Impian Kita',
        'content' => 'Semoga tahun depan kita bisa beli rumah impian.',
        'visibility' => 'shared',
        'entry_date' => now()->toDateString(),
    ]);

    // Syahra can view shared journal
    $syahraResponse = $this->actingAs($this->syahra)->get("/journals/{$sharedJournal->id}");
    $syahraResponse->assertOk();
    $syahraResponse->assertSee('Impian Rumah Impian Kita');
});

test('child role cannot access journals', function () {
    $child = User::create([
        'couple_id' => $this->couple->id,
        'name' => 'Rayyan',
        'email' => 'rayyan@test.local',
        'password' => Hash::make('Password123!'),
        'role' => 'child',
        'must_change_password' => false,
        'is_active' => true,
    ]);

    $response = $this->actingAs($child)->get('/journals');
    $response->assertStatus(403);
});
