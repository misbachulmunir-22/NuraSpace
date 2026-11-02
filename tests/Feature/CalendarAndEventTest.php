<?php

use App\Models\Couple;
use App\Models\Event;
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

test('partner can create event with category and color', function () {
    $response = $this->actingAs($this->munir)->post('/events', [
        'title' => 'Romantic Dinner Anniversary',
        'description' => 'Makan malam romantis di rooftop.',
        'category' => 'date_night',
        'color' => '#f43f5e',
        'start_at' => now()->addDays(2)->format('Y-m-d H:i:s'),
        'is_all_day' => false,
        'is_family_event' => false,
    ]);

    $response->assertRedirect(route('events.index'));

    $event = Event::where('title', 'Romantic Dinner Anniversary')->first();
    expect($event)->not->toBeNull();
    expect($event->category)->toBe('date_night');
    expect($event->category_label)->toBe('Kencan & Romantis');
    expect($event->color)->toBe('#f43f5e');
    expect($event->is_family_event)->toBeFalse();
});

test('child can view family events but NOT private partner events', function () {
    // Private partner event
    Event::create([
        'couple_id' => $this->couple->id,
        'created_by_user_id' => $this->munir->id,
        'title' => 'Kencan Privat Berdua',
        'category' => 'date_night',
        'color' => '#f43f5e',
        'start_at' => now()->addDays(1),
        'is_family_event' => false,
    ]);

    // Family shared event
    Event::create([
        'couple_id' => $this->couple->id,
        'created_by_user_id' => $this->munir->id,
        'title' => 'Piknik Bersama Rayyan',
        'category' => 'family',
        'color' => '#8b5cf6',
        'start_at' => now()->addDays(3),
        'is_family_event' => true,
    ]);

    // Partner sees both
    $partnerResponse = $this->actingAs($this->munir)->get('/events');
    $partnerResponse->assertSee('Kencan Privat Berdua');
    $partnerResponse->assertSee('Piknik Bersama Rayyan');

    // Child ONLY sees family event
    $childResponse = $this->actingAs($this->child)->get('/events');
    $childResponse->assertSee('Piknik Bersama Rayyan');
    $childResponse->assertDontSee('Kencan Privat Berdua');
});

test('events feed endpoint returns json filtered for role', function () {
    Event::create([
        'couple_id' => $this->couple->id,
        'created_by_user_id' => $this->munir->id,
        'title' => 'Kencan Privat Partner',
        'category' => 'date_night',
        'color' => '#f43f5e',
        'start_at' => now()->addDays(1),
        'is_family_event' => false,
    ]);

    Event::create([
        'couple_id' => $this->couple->id,
        'created_by_user_id' => $this->munir->id,
        'title' => 'Liburan Keluarga Taman Safari',
        'category' => 'family',
        'color' => '#8b5cf6',
        'start_at' => now()->addDays(2),
        'is_family_event' => true,
    ]);

    $childFeed = $this->actingAs($this->child)->getJson('/events/feed');
    $childFeed->assertOk();
    $childData = $childFeed->json();

    expect(count($childData))->toBe(1);
    expect($childData[0]['title'])->toBe('Liburan Keluarga Taman Safari');
});

test('partner can update and delete an event', function () {
    $event = Event::create([
        'couple_id' => $this->couple->id,
        'created_by_user_id' => $this->munir->id,
        'title' => 'Nonton Film Bioskop',
        'category' => 'date_night',
        'color' => '#f43f5e',
        'start_at' => now()->addDays(4),
        'is_family_event' => true,
    ]);

    $res = $this->actingAs($this->munir)->put("/events/{$event->id}", [
        'title' => 'Nonton Film Bioskop IMAX',
        'category' => 'date_night',
        'start_at' => now()->addDays(4)->format('Y-m-d H:i:s'),
    ]);
    $res->assertSessionHasNoErrors();
    $res->assertRedirect(route('events.index'));

    $event->refresh();
    expect($event->title)->toBe('Nonton Film Bioskop IMAX');

    $delResponse = $this->actingAs($this->munir)->delete("/events/{$event->id}");
    $delResponse->assertRedirect(route('events.index'));
    expect(Event::where('id', $event->id)->exists())->toBeFalse();
});
