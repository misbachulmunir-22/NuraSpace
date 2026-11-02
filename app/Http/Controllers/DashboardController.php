<?php

namespace App\Http\Controllers;

use App\Models\Countdown;
use App\Models\DailyQuestionCouple;
use App\Models\Event;
use App\Models\Memory;
use App\Models\Mood;
use App\Models\SavingsGoal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Integrated Mobile-First Dashboard.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $couple = $user->couple;
        $today = Carbon::now('Asia/Jakarta')->toDateString();

        // Warm Indonesian Greeting based on WIB hour
        $hour = Carbon::now('Asia/Jakarta')->hour;
        $greeting = match (true) {
            $hour >= 4 && $hour < 11 => 'Selamat Pagi',
            $hour >= 11 && $hour < 15 => 'Selamat Siang',
            $hour >= 15 && $hour < 18 => 'Selamat Sore',
            default => 'Selamat Malam',
        };

        if ($user->isPartner()) {
            // 1. Partner
            $partner = $couple->partners()->where('id', '!=', $user->id)->first();

            // 2. Moods today
            $myMood = Mood::where('user_id', $user->id)->whereDate('logged_date', $today)->first();
            $partnerMood = $partner ? Mood::where('user_id', $partner->id)->whereDate('logged_date', $today)->first() : null;

            // 3. Daily question today
            $dailyQuestionRecord = DailyQuestionCouple::withoutGlobalScopes()
                ->where('couple_id', $couple->id)
                ->whereDate('served_date', $today)
                ->with(['question', 'answers.user'])
                ->first();

            $myAnswer = $dailyQuestionRecord ? $dailyQuestionRecord->answers->firstWhere('user_id', $user->id) : null;
            $partnerAnswer = ($dailyQuestionRecord && $partner) ? $dailyQuestionRecord->answers->firstWhere('user_id', $partner->id) : null;
            $bothAnswered = $myAnswer !== null && $partnerAnswer !== null;

            // 4. Pinned or nearest countdown
            $countdown = Countdown::where('couple_id', $couple->id)
                ->where('target_date', '>=', now())
                ->orderByDesc('is_pinned')
                ->orderBy('target_date', 'asc')
                ->first();

            // 5. Recent memory with media
            $recentMemory = Memory::where('couple_id', $couple->id)
                ->with('media')
                ->latest('memory_date')
                ->first();

            // 6. Active savings goal
            $activeGoal = SavingsGoal::where('couple_id', $couple->id)
                ->where('status', 'active')
                ->orderBy('target_date', 'asc')
                ->first();

            // 7. Next upcoming event
            $nextEvent = Event::where('couple_id', $couple->id)
                ->where('start_at', '>=', now())
                ->orderBy('start_at', 'asc')
                ->first();

            return view('dashboard', [
                'user' => $user,
                'greeting' => $greeting,
                'isPartner' => true,
                'partner' => $partner,
                'myMood' => $myMood,
                'partnerMood' => $partnerMood,
                'dailyQuestionRecord' => $dailyQuestionRecord,
                'myAnswer' => $myAnswer,
                'partnerAnswer' => $bothAnswered ? $partnerAnswer : null,
                'hasPartnerAnswered' => $partnerAnswer !== null,
                'bothAnswered' => $bothAnswered,
                'countdown' => $countdown,
                'recentMemory' => $recentMemory,
                'activeGoal' => $activeGoal,
                'nextEvent' => $nextEvent,
            ]);
        }

        // Child View (Family-safe: only family calendar, memories, countdown, and nudges)
        $parents = $couple->partners()->get();

        $nextFamilyEvent = Event::where('couple_id', $couple->id)
            ->where('is_family_event', true)
            ->where('start_at', '>=', now())
            ->orderBy('start_at', 'asc')
            ->first();

        $countdown = Countdown::where('couple_id', $couple->id)
            ->where('target_date', '>=', now())
            ->orderByDesc('is_pinned')
            ->orderBy('target_date', 'asc')
            ->first();

        $recentMemory = Memory::where('couple_id', $couple->id)
            ->with('media')
            ->latest('memory_date')
            ->first();

        return view('dashboard', [
            'user' => $user,
            'greeting' => $greeting,
            'isPartner' => false,
            'parents' => $parents,
            'nextFamilyEvent' => $nextFamilyEvent,
            'countdown' => $countdown,
            'recentMemory' => $recentMemory,
        ]);
    }
}
