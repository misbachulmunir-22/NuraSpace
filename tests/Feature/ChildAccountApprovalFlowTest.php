<?php

use App\Models\ChildAccountApproval;
use App\Models\ChildAccountRequest;
use App\Models\ChildDeletionApproval;
use App\Models\ChildDeletionRequest;
use App\Models\Couple;
use App\Models\User;
use App\Notifications\ChildAccountApprovalNeededNotification;
use App\Notifications\ChildAccountStatusNotification;
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

test('only partner can access family page and child is forbidden', function () {
    $child = User::create([
        'couple_id' => $this->couple->id,
        'name' => 'Rayyan',
        'email' => 'rayyan@test.local',
        'password' => Hash::make('Password123!'),
        'role' => 'child',
        'must_change_password' => false,
        'is_active' => true,
    ]);

    // Partner can access
    $partnerResponse = $this->actingAs($this->munir)->get('/family');
    $partnerResponse->assertOk();

    // Child cannot access
    $childResponse = $this->actingAs($child)->get('/family');
    $childResponse->assertStatus(403);
});

test('submitting child account request creates approvals and notifies the other partner without creating user yet', function () {
    Notification::fake();

    $response = $this->actingAs($this->munir)->post('/family/child-requests', [
        'name' => 'Muhammad Rayyan Munir',
        'email' => 'rayyan@test.local',
        'birth_date' => '2023-01-15',
        'gender' => 'male',
        'initial_password' => 'RayyanPassword123!',
    ]);

    $response->assertRedirect(route('family.index'));
    $response->assertSessionHas('success');

    $requestRecord = ChildAccountRequest::where('email', 'rayyan@test.local')->first();
    expect($requestRecord)->not->toBeNull();
    expect($requestRecord->status)->toBe('pending');
    expect($requestRecord->requested_by_user_id)->toBe($this->munir->id);

    // Approvals: Munir auto-approved, Syahra pending
    $munirApproval = ChildAccountApproval::where('request_id', $requestRecord->id)
        ->where('partner_user_id', $this->munir->id)
        ->first();
    $syahraApproval = ChildAccountApproval::where('request_id', $requestRecord->id)
        ->where('partner_user_id', $this->syahra->id)
        ->first();

    expect($munirApproval->status)->toBe('approved');
    expect($syahraApproval->status)->toBe('pending');

    // Child user is NOT created yet
    expect(User::where('email', 'rayyan@test.local')->exists())->toBeFalse();

    // Syahra received notification
    Notification::assertSentTo($this->syahra, ChildAccountApprovalNeededNotification::class);
});

test('child account is created only when second partner approves', function () {
    Notification::fake();

    $childRequest = ChildAccountRequest::create([
        'couple_id' => $this->couple->id,
        'requested_by_user_id' => $this->munir->id,
        'name' => 'Muhammad Rayyan Munir',
        'email' => 'rayyan@test.local',
        'birth_date' => '2023-01-15',
        'gender' => 'male',
        'initial_password' => Hash::make('RayyanPassword123!'),
        'status' => 'pending',
    ]);

    ChildAccountApproval::create([
        'request_id' => $childRequest->id,
        'partner_user_id' => $this->munir->id,
        'status' => 'approved',
        'responded_at' => now(),
    ]);

    ChildAccountApproval::create([
        'request_id' => $childRequest->id,
        'partner_user_id' => $this->syahra->id,
        'status' => 'pending',
    ]);

    // Syahra approves
    $response = $this->actingAs($this->syahra)->post("/family/child-requests/{$childRequest->id}/approve", [
        'notes' => 'Disetujui dengan senang hati.',
    ]);

    $response->assertRedirect(route('family.index'));
    $response->assertSessionHas('success');

    $childRequest->refresh();
    expect($childRequest->status)->toBe('approved');
    expect($childRequest->created_child_user_id)->not->toBeNull();

    // Child user exists in database
    $childUser = User::where('email', 'rayyan@test.local')->first();
    expect($childUser)->not->toBeNull();
    expect($childUser->name)->toBe('Muhammad Rayyan Munir');
    expect($childUser->role)->toBe('child');
    expect($childUser->couple_id)->toBe($this->couple->id);
    expect($childUser->is_active)->toBeTrue();
    expect($childUser->must_change_password)->toBeTrue();

    // Both partners notified of successful creation
    Notification::assertSentTo($this->munir, ChildAccountStatusNotification::class);
    Notification::assertSentTo($this->syahra, ChildAccountStatusNotification::class);
});

test('child account request is rejected if one partner rejects', function () {
    $childRequest = ChildAccountRequest::create([
        'couple_id' => $this->couple->id,
        'requested_by_user_id' => $this->munir->id,
        'name' => 'Muhammad Rayyan Munir',
        'email' => 'rayyan@test.local',
        'birth_date' => '2023-01-15',
        'gender' => 'male',
        'initial_password' => Hash::make('RayyanPassword123!'),
        'status' => 'pending',
    ]);

    ChildAccountApproval::create([
        'request_id' => $childRequest->id,
        'partner_user_id' => $this->munir->id,
        'status' => 'approved',
        'responded_at' => now(),
    ]);

    ChildAccountApproval::create([
        'request_id' => $childRequest->id,
        'partner_user_id' => $this->syahra->id,
        'status' => 'pending',
    ]);

    // Syahra rejects
    $response = $this->actingAs($this->syahra)->post("/family/child-requests/{$childRequest->id}/reject", [
        'notes' => 'Belum cukup umur.',
    ]);

    $response->assertRedirect(route('family.index'));

    $childRequest->refresh();
    expect($childRequest->status)->toBe('rejected');
    expect(User::where('email', 'rayyan@test.local')->exists())->toBeFalse();
});

test('partner can activate and deactivate child account', function () {
    $child = User::create([
        'couple_id' => $this->couple->id,
        'name' => 'Rayyan',
        'email' => 'rayyan@test.local',
        'password' => Hash::make('Password123!'),
        'role' => 'child',
        'must_change_password' => false,
        'is_active' => true,
    ]);

    // Deactivate
    $response = $this->actingAs($this->munir)->post("/family/children/{$child->id}/toggle-active");
    $response->assertRedirect(route('family.index'));
    $child->refresh();
    expect($child->is_active)->toBeFalse();

    // Activate
    $response2 = $this->actingAs($this->syahra)->post("/family/children/{$child->id}/toggle-active");
    $response2->assertRedirect(route('family.index'));
    $child->refresh();
    expect($child->is_active)->toBeTrue();
});

test('partner can reset child password', function () {
    $child = User::create([
        'couple_id' => $this->couple->id,
        'name' => 'Rayyan',
        'email' => 'rayyan@test.local',
        'password' => Hash::make('OldPassword123!'),
        'role' => 'child',
        'must_change_password' => false,
        'is_active' => true,
    ]);

    $response = $this->actingAs($this->munir)->post("/family/children/{$child->id}/reset-password", [
        'password' => 'NewResetPassword123!',
        'password_confirmation' => 'NewResetPassword123!',
    ]);

    $response->assertRedirect(route('family.index'));
    $child->refresh();
    expect(Hash::check('NewResetPassword123!', $child->password))->toBeTrue();
    expect($child->must_change_password)->toBeTrue();
});

test('child account deletion requires dual partner approval', function () {
    $child = User::create([
        'couple_id' => $this->couple->id,
        'name' => 'Rayyan',
        'email' => 'rayyan@test.local',
        'password' => Hash::make('Password123!'),
        'role' => 'child',
        'must_change_password' => false,
        'is_active' => true,
    ]);

    // Munir requests deletion
    $response = $this->actingAs($this->munir)->post("/family/children/{$child->id}/request-deletion", [
        'reason' => 'Tes penghapusan anak',
    ]);

    $response->assertRedirect(route('family.index'));

    $delRequest = ChildDeletionRequest::where('child_user_id', $child->id)->first();
    expect($delRequest)->not->toBeNull();
    expect($delRequest->status)->toBe('pending');

    // Child is still alive in db
    expect(User::where('id', $child->id)->exists())->toBeTrue();

    // Syahra approves deletion
    $responseAppr = $this->actingAs($this->syahra)->post("/family/deletion-requests/{$delRequest->id}/approve");
    $responseAppr->assertRedirect(route('family.index'));

    // Now child is deleted
    expect(User::where('id', $child->id)->exists())->toBeFalse();
    $delRequest->refresh();
    expect($delRequest->status)->toBe('approved');
});
