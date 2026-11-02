<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'password.changed', 'couple'])
    ->name('dashboard');

Route::middleware(['auth', 'password.changed', 'couple'])->group(function () {
    Route::get('/family', [\App\Http\Controllers\FamilyController::class, 'index'])->name('family.index');
    Route::post('/family/child-requests', [\App\Http\Controllers\FamilyController::class, 'storeRequest'])->name('family.child-requests.store');
    Route::post('/family/child-requests/{childAccountRequest}/approve', [\App\Http\Controllers\FamilyController::class, 'approve'])->name('family.child-requests.approve');
    Route::post('/family/child-requests/{childAccountRequest}/reject', [\App\Http\Controllers\FamilyController::class, 'reject'])->name('family.child-requests.reject');
    Route::post('/family/children/{child}/toggle-active', [\App\Http\Controllers\FamilyController::class, 'toggleActive'])->name('family.children.toggle-active');
    Route::post('/family/children/{child}/reset-password', [\App\Http\Controllers\FamilyController::class, 'resetChildPassword'])->name('family.children.reset-password');
    Route::post('/family/children/{child}/request-deletion', [\App\Http\Controllers\FamilyController::class, 'requestDeletion'])->name('family.children.request-deletion');
    Route::post('/family/deletion-requests/{deletionRequest}/approve', [\App\Http\Controllers\FamilyController::class, 'approveDeletion'])->name('family.deletion-requests.approve');

    // Calendar & Events (FR-03.1 & FR-03.2)
    Route::get('/events', [\App\Http\Controllers\CalendarController::class, 'index'])->name('events.index');
    Route::get('/events/feed', [\App\Http\Controllers\CalendarController::class, 'feed'])->name('events.feed');
    Route::post('/events', [\App\Http\Controllers\CalendarController::class, 'store'])->name('events.store');
    Route::put('/events/{event}', [\App\Http\Controllers\CalendarController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [\App\Http\Controllers\CalendarController::class, 'destroy'])->name('events.destroy');

    // Countdowns (FR-03.3)
    Route::get('/countdowns', [\App\Http\Controllers\CountdownController::class, 'index'])->name('countdowns.index');
    Route::post('/countdowns', [\App\Http\Controllers\CountdownController::class, 'store'])->name('countdowns.store');
    Route::put('/countdowns/{countdown}/pin', [\App\Http\Controllers\CountdownController::class, 'togglePin'])->name('countdowns.pin');
    Route::delete('/countdowns/{countdown}', [\App\Http\Controllers\CountdownController::class, 'destroy'])->name('countdowns.destroy');

    // Nudges (FR-04.1) - Virtual touches (Hug, Kiss, Miss You)
    Route::post('/nudges', [\App\Http\Controllers\NudgeController::class, 'store'])->name('nudges.store');
    Route::get('/nudges/recent', [\App\Http\Controllers\NudgeController::class, 'recent'])->name('nudges.recent');
    Route::post('/nudges/{nudge}/read', [\App\Http\Controllers\NudgeController::class, 'markRead'])->name('nudges.read');

    // Gallery & Memory Lane (FR-05)
    Route::get('/memories', [\App\Http\Controllers\MemoryController::class, 'index'])->name('memories.index');
    Route::post('/memories', [\App\Http\Controllers\MemoryController::class, 'store'])->name('memories.store');
    Route::get('/memories/{memory}', [\App\Http\Controllers\MemoryController::class, 'show'])->name('memories.show');
    Route::delete('/memories/{memory}', [\App\Http\Controllers\MemoryController::class, 'destroy'])->name('memories.destroy');
});

Route::middleware(['auth', 'password.changed', 'couple', 'role:partner'])->group(function () {
    // Mood Tracker (FR-02.1)
    Route::post('/moods', [\App\Http\Controllers\MoodController::class, 'store'])->name('moods.store');

    // Shared & Private Journals (FR-02.2)
    Route::resource('journals', \App\Http\Controllers\JournalController::class);

    // Sealed Letters & Time Capsules (FR-02.3)
    Route::resource('time-capsules', \App\Http\Controllers\TimeCapsuleController::class)->except(['edit', 'update']);

    // Daily Question (FR-04.2)
    Route::get('/daily-question', [\App\Http\Controllers\DailyQuestionController::class, 'index'])->name('daily-question.index');
    Route::post('/daily-question/answer', [\App\Http\Controllers\DailyQuestionController::class, 'storeAnswer'])->name('daily-question.answer');
    Route::get('/daily-question/history', [\App\Http\Controllers\DailyQuestionController::class, 'history'])->name('daily-question.history');

    // Couple Wishlist (FR-06.1)
    Route::get('/wishlists', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlists.index');
    Route::post('/wishlists', [\App\Http\Controllers\WishlistController::class, 'store'])->name('wishlists.store');
    Route::put('/wishlists/{wishlist}', [\App\Http\Controllers\WishlistController::class, 'update'])->name('wishlists.update');
    Route::patch('/wishlists/{wishlist}/status', [\App\Http\Controllers\WishlistController::class, 'toggleStatus'])->name('wishlists.status');
    Route::delete('/wishlists/{wishlist}', [\App\Http\Controllers\WishlistController::class, 'destroy'])->name('wishlists.destroy');

    // Financial / Savings Tracker (FR-06.2)
    Route::get('/savings', [\App\Http\Controllers\SavingsController::class, 'index'])->name('savings.index');
    Route::post('/savings/goals', [\App\Http\Controllers\SavingsController::class, 'storeGoal'])->name('savings.goals.store');
    Route::get('/savings/goals/{savingsGoal}', [\App\Http\Controllers\SavingsController::class, 'show'])->name('savings.show');
    Route::delete('/savings/goals/{savingsGoal}', [\App\Http\Controllers\SavingsController::class, 'destroyGoal'])->name('savings.goals.destroy');
    Route::post('/savings/goals/{savingsGoal}/transactions', [\App\Http\Controllers\SavingsController::class, 'storeTransaction'])->name('savings.transactions.store');
    Route::delete('/savings/transactions/{savingsTransaction}', [\App\Http\Controllers\SavingsController::class, 'destroyTransaction'])->name('savings.transactions.destroy');
});

// Private Media Delivery (via Signed URL or authenticated session)
Route::get('/media/memories/{memoryMedia}', [\App\Http\Controllers\MediaController::class, 'serveMemory'])->name('media.memory');
Route::get('/media/wishlists/{wishlist}', [\App\Http\Controllers\MediaController::class, 'serveWishlist'])->name('media.wishlist');

Route::middleware(['auth', 'couple'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
