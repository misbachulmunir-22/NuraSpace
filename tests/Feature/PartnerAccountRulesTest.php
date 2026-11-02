<?php

use App\Models\Couple;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Hash;

test('database seeder creates exactly two partner accounts paired in the same couple', function () {
    $this->seed(DatabaseSeeder::class);

    $partners = User::where('role', 'partner')->get();
    expect($partners)->toHaveCount(2);

    $couple = Couple::first();
    expect($couple)->not->toBeNull();

    expect($partners[0]->couple_id)->toBe($couple->id);
    expect($partners[1]->couple_id)->toBe($couple->id);

    expect(['munir@nuraspace.local', 'syahra@nuraspace.local'])->toContain($partners[0]->email);
    expect(['munir@nuraspace.local', 'syahra@nuraspace.local'])->toContain($partners[1]->email);

    expect($partners[0]->must_change_password)->toBeTrue();
    expect($partners[1]->must_change_password)->toBeTrue();
});

test('partner must change initial password before accessing dashboard', function () {
    $couple = Couple::create(['name' => 'Test Couple']);
    $partner = User::create([
        'couple_id' => $couple->id,
        'name' => 'Misbachul Munir',
        'email' => 'munir@test.local',
        'password' => Hash::make('InitialPassword123!'),
        'role' => 'partner',
        'must_change_password' => true,
        'is_active' => true,
    ]);

    $response = $this->actingAs($partner)->get('/dashboard');
    $response->assertRedirect(route('profile.edit'));
    $response->assertSessionHas('warning');
});

test('partner can change password and then access dashboard', function () {
    $couple = Couple::create(['name' => 'Test Couple']);
    $partner = User::create([
        'couple_id' => $couple->id,
        'name' => 'Misbachul Munir',
        'email' => 'munir@test.local',
        'password' => Hash::make('InitialPassword123!'),
        'role' => 'partner',
        'must_change_password' => true,
        'is_active' => true,
    ]);

    $response = $this->actingAs($partner)->put('/password', [
        'current_password' => 'InitialPassword123!',
        'password' => 'NewSecretPassword123!',
        'password_confirmation' => 'NewSecretPassword123!',
    ]);

    $response->assertSessionHasNoErrors();
    $partner->refresh();
    expect($partner->must_change_password)->toBeFalse();
    expect(Hash::check('NewSecretPassword123!', $partner->password))->toBeTrue();

    // Now dashboard can be accessed
    $dashboardResponse = $this->actingAs($partner)->get('/dashboard');
    $dashboardResponse->assertStatus(200);
});

test('partner account cannot be deleted via controller', function () {
    $couple = Couple::create(['name' => 'Test Couple']);
    $partner = User::create([
        'couple_id' => $couple->id,
        'name' => 'Misbachul Munir',
        'email' => 'munir@test.local',
        'password' => Hash::make('Password123!'),
        'role' => 'partner',
        'must_change_password' => false,
        'is_active' => true,
    ]);

    $response = $this->actingAs($partner)->delete('/profile', [
        'password' => 'Password123!',
    ]);

    $response->assertStatus(403);
    expect(User::where('id', $partner->id)->exists())->toBeTrue();
});

test('partner account deletion throws RuntimeException at model level', function () {
    $couple = Couple::create(['name' => 'Test Couple']);
    $partner = User::create([
        'couple_id' => $couple->id,
        'name' => 'Syahra Alfi Nur Arintia',
        'email' => 'syahra@test.local',
        'password' => Hash::make('Password123!',),
        'role' => 'partner',
        'must_change_password' => false,
        'is_active' => true,
    ]);

    expect(fn () => $partner->delete())->toThrow(RuntimeException::class, 'Akun partner utama tidak boleh dihapus.');
    expect(User::where('id', $partner->id)->exists())->toBeTrue();
});

test('inactive user is blocked and redirected to login', function () {
    $couple = Couple::create(['name' => 'Test Couple']);
    $user = User::create([
        'couple_id' => $couple->id,
        'name' => 'Suspended Child',
        'email' => 'child@test.local',
        'password' => Hash::make('Password123!'),
        'role' => 'child',
        'must_change_password' => false,
        'is_active' => false,
    ]);

    $response = $this->actingAs($user)->get('/dashboard');
    $response->assertRedirect(route('login'));
    $this->assertGuest();
});
