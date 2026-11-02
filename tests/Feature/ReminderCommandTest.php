<?php

use App\Models\Couple;
use App\Models\ReminderLog;
use App\Models\User;
use App\Notifications\AnniversaryReminderNotification;
use App\Notifications\BirthdayReminderNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

test('reminder command sends anniversary notification at H-7 and avoids duplicate sending', function () {
    Notification::fake();

    // Anniversary is exactly 7 days from today
    $targetAnnivDate = now()->addDays(7)->subYears(2)->toDateString();

    $couple = Couple::create([
        'name' => 'Munir & Syahra Space',
        'anniversary_date' => $targetAnnivDate,
    ]);

    $munir = User::create([
        'couple_id' => $couple->id,
        'name' => 'Misbachul Munir',
        'email' => 'munir@test.local',
        'password' => Hash::make('Password123!'),
        'role' => 'partner',
        'must_change_password' => false,
        'is_active' => true,
    ]);

    $syahra = User::create([
        'couple_id' => $couple->id,
        'name' => 'Syahra Alfi Nur Arintia',
        'email' => 'syahra@test.local',
        'password' => Hash::make('Password123!'),
        'role' => 'partner',
        'must_change_password' => false,
        'is_active' => true,
    ]);

    // Run command first time
    $this->artisan('nuraspace:send-reminders')
        ->expectsOutputToContain('Pengingat anniversary (h_minus_7) terkirim')
        ->assertSuccessful();

    Notification::assertSentTo($munir, AnniversaryReminderNotification::class, function ($notification) {
        return $notification->stage === 'h_minus_7';
    });
    Notification::assertSentTo($syahra, AnniversaryReminderNotification::class);

    expect(ReminderLog::where('reminder_type', 'anniversary')->where('stage', 'h_minus_7')->exists())->toBeTrue();

    // Run command second time -> should NOT send duplicate
    Notification::fake();
    $this->artisan('nuraspace:send-reminders')->assertSuccessful();
    Notification::assertNothingSent();
});

test('reminder command sends birthday notification at H-1 for members', function () {
    Notification::fake();

    $couple = Couple::create(['name' => 'Munir & Syahra Space']);

    $munir = User::create([
        'couple_id' => $couple->id,
        'name' => 'Misbachul Munir',
        'email' => 'munir@test.local',
        'password' => Hash::make('Password123!'),
        'role' => 'partner',
        'must_change_password' => false,
        'is_active' => true,
    ]);

    // Syahra's birthday is tomorrow (H-1)
    $syahraBirthday = now()->addDay()->subYears(25)->toDateString();

    $syahra = User::create([
        'couple_id' => $couple->id,
        'name' => 'Syahra Alfi Nur Arintia',
        'email' => 'syahra@test.local',
        'password' => Hash::make('Password123!'),
        'role' => 'partner',
        'birth_date' => $syahraBirthday,
        'must_change_password' => false,
        'is_active' => true,
    ]);

    $this->artisan('nuraspace:send-reminders')
        ->expectsOutputToContain('Pengingat ulang tahun Syahra Alfi Nur Arintia (h_minus_1) terkirim')
        ->assertSuccessful();

    Notification::assertSentTo($munir, BirthdayReminderNotification::class, function ($notification) use ($syahra) {
        return $notification->stage === 'h_minus_1' && $notification->birthdayUser->id === $syahra->id;
    });

    expect(ReminderLog::where('reminder_type', 'birthday')->where('stage', 'h_minus_1')->exists())->toBeTrue();
});
