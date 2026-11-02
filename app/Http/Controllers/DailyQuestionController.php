<?php

namespace App\Http\Controllers;

use App\Events\DailyQuestionAnsweredEvent;
use App\Models\DailyQuestion;
use App\Models\DailyQuestionAnswer;
use App\Models\DailyQuestionCouple;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DailyQuestionController extends Controller
{
    /**
     * Show today's couple question.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $today = Carbon::now('Asia/Jakarta')->toDateString();

        // 1. Get or assign today's question for this couple
        $todayRecord = DailyQuestionCouple::withoutGlobalScope(\App\Models\Scopes\CoupleScope::class)
            ->where('couple_id', $user->couple_id)
            ->whereDate('served_date', $today)
            ->with(['question', 'answers.user'])
            ->first();

        if (!$todayRecord) {
            // Find next unserved question for this couple
            $servedQuestionIds = DailyQuestionCouple::withoutGlobalScope(\App\Models\Scopes\CoupleScope::class)
                ->where('couple_id', $user->couple_id)
                ->pluck('daily_question_id');

            $nextQuestion = DailyQuestion::whereNotIn('id', $servedQuestionIds)
                ->orderBy('order_index')
                ->first();

            // If all 105 questions have been served, pick random or cycle back
            if (!$nextQuestion) {
                $nextQuestion = DailyQuestion::inRandomOrder()->first();
            }

            // Fallback if seeder hasn't run yet
            if (!$nextQuestion) {
                $nextQuestion = DailyQuestion::create([
                    'question_text' => 'Apa satu hal manis yang paling kamu syukuri dari pasanganmu hari ini?',
                    'category' => 'gratitude',
                    'order_index' => 1,
                ]);
            }

            $todayRecord = DailyQuestionCouple::create([
                'couple_id' => $user->couple_id,
                'daily_question_id' => $nextQuestion->id,
                'served_date' => $today,
            ]);

            $todayRecord->load(['question', 'answers.user']);
        }

        // 2. Identify partner
        $partner = $user->couple->partners()
            ->where('id', '!=', $user->id)
            ->first();

        // 3. Check answers
        $myAnswer = $todayRecord->answers->firstWhere('user_id', $user->id);
        $partnerAnswer = $partner ? $todayRecord->answers->firstWhere('user_id', $partner->id) : null;

        $hasPartnerAnswered = $partnerAnswer !== null;
        $bothAnswered = $myAnswer !== null && $hasPartnerAnswered;

        // Privacy protection: partner answer text is STRICTLY null unless both have answered!
        $safePartnerAnswer = $bothAnswered ? $partnerAnswer : null;

        // 4. Get recent completed questions (last 5)
        $recentCompleted = DailyQuestionCouple::withoutGlobalScope(\App\Models\Scopes\CoupleScope::class)
            ->where('couple_id', $user->couple_id)
            ->where('id', '!=', $todayRecord->id)
            ->whereHas('answers', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->with(['question', 'answers.user'])
            ->orderByDesc('served_date')
            ->take(3)
            ->get();

        return view('daily_question.index', [
            'todayRecord' => $todayRecord,
            'question' => $todayRecord->question,
            'myAnswer' => $myAnswer,
            'partner' => $partner,
            'hasPartnerAnswered' => $hasPartnerAnswered,
            'partnerAnswer' => $safePartnerAnswer,
            'bothAnswered' => $bothAnswered,
            'recentCompleted' => $recentCompleted,
        ]);
    }

    /**
     * Submit or update user's answer to today's question.
     */
    public function storeAnswer(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'daily_question_couple_id' => ['required', 'integer'],
            'answer_text' => ['required', 'string', 'min:2', 'max:1500'],
        ]);

        $coupleQuestion = DailyQuestionCouple::where('id', $validated['daily_question_couple_id'])
            ->where('couple_id', $user->couple_id)
            ->firstOrFail();

        DailyQuestionAnswer::updateOrCreate(
            [
                'couple_id' => $user->couple_id,
                'daily_question_couple_id' => $coupleQuestion->id,
                'user_id' => $user->id,
            ],
            [
                'answer_text' => $validated['answer_text'],
            ]
        );

        // Check if both partners have now answered
        $coupleQuestion->refresh();
        $areBothAnswered = $coupleQuestion->areBothAnswersSubmitted();

        // Broadcast event
        event(new DailyQuestionAnsweredEvent($coupleQuestion, $user, $areBothAnswered));

        if ($areBothAnswered) {
            $msg = 'Jawabanmu berhasil disimpan! Hore, kalian berdua sudah menjawab! Kunci jawaban kini terbuka! 🎉💖';
        } else {
            $msg = 'Jawabanmu berhasil disimpan dengan aman! Menunggu pasanganmu menjawab untuk membuka jawabannya bersama-sama. 🔒💌';
        }

        return redirect()->route('daily-question.index')->with('status', $msg);
    }

    /**
     * View history of all daily questions and answers.
     */
    public function history(Request $request): View
    {
        $user = $request->user();

        $history = DailyQuestionCouple::withoutGlobalScope(\App\Models\Scopes\CoupleScope::class)
            ->where('couple_id', $user->couple_id)
            ->with(['question', 'answers.user'])
            ->orderByDesc('served_date')
            ->paginate(10);

        return view('daily_question.history', [
            'history' => $history,
            'currentUserId' => $user->id,
        ]);
    }
}
