<?php

namespace App\Console\Commands;

use App\Models\Couple;
use App\Models\ReminderLog;
use App\Notifications\AnniversaryReminderNotification;
use App\Notifications\BirthdayReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendAnniversaryAndBirthdayRemindersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nuraspace:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim pengingat otomatis anniversary dan ulang tahun anggota keluarga (H-7, H-1, dan hari-H).';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $today = now()->startOfDay();
        $targetYear = (int) $today->format('Y');

        $couples = Couple::with(['partners', 'users'])->get();

        foreach ($couples as $couple) {
            // 1. Anniversary Reminder
            if ($couple->anniversary_date) {
                $annivDate = Carbon::parse($couple->anniversary_date);
                $annivThisYear = Carbon::createFromDate(
                    $targetYear,
                    $annivDate->month,
                    $annivDate->day
                )->startOfDay();

                $daysToAnniv = (int) $today->diffInDays($annivThisYear, false);

                $stage = match ($daysToAnniv) {
                    7 => 'h_minus_7',
                    1 => 'h_minus_1',
                    0 => 'day_of',
                    default => null,
                };

                if ($stage) {
                    $alreadySent = ReminderLog::where('couple_id', $couple->id)
                        ->where('reminder_type', 'anniversary')
                        ->where('target_year', $targetYear)
                        ->where('stage', $stage)
                        ->exists();

                    if (!$alreadySent) {
                        $years = max(1, $targetYear - (int) $annivDate->year);

                        foreach ($couple->partners as $partner) {
                            $partner->notify(new AnniversaryReminderNotification($couple, $stage, $years));
                        }

                        ReminderLog::create([
                            'couple_id' => $couple->id,
                            'reminder_type' => 'anniversary',
                            'target_id' => null,
                            'target_year' => $targetYear,
                            'stage' => $stage,
                            'sent_at' => now(),
                        ]);

                        $this->info("Pengingat anniversary ({$stage}) terkirim untuk {$couple->name}.");
                    }
                }
            }

            // 2. Birthday Reminder for all members (partners & children)
            foreach ($couple->users as $member) {
                if ($member->birth_date) {
                    $bdayDate = Carbon::parse($member->birth_date);
                    $bdayThisYear = Carbon::createFromDate(
                        $targetYear,
                        $bdayDate->month,
                        $bdayDate->day
                    )->startOfDay();

                    $daysToBday = (int) $today->diffInDays($bdayThisYear, false);

                    $stage = match ($daysToBday) {
                        7 => 'h_minus_7',
                        1 => 'h_minus_1',
                        0 => 'day_of',
                        default => null,
                    };

                    if ($stage) {
                        $alreadySent = ReminderLog::where('couple_id', $couple->id)
                            ->where('reminder_type', 'birthday')
                            ->where('target_id', $member->id)
                            ->where('target_year', $targetYear)
                            ->where('stage', $stage)
                            ->exists();

                        if (!$alreadySent) {
                            $age = max(1, $targetYear - (int) $member->birth_date->year);

                            // Send reminder to other partners/family members
                            foreach ($couple->partners as $partner) {
                                $partner->notify(new BirthdayReminderNotification($member, $stage, $age));
                            }

                            ReminderLog::create([
                                'couple_id' => $couple->id,
                                'reminder_type' => 'birthday',
                                'target_id' => $member->id,
                                'target_year' => $targetYear,
                                'stage' => $stage,
                                'sent_at' => now(),
                            ]);

                            $this->info("Pengingat ulang tahun {$member->name} ({$stage}) terkirim.");
                        }
                    }
                }
            }
        }

        $this->info('Pemeriksaan pengingat anniversary dan ulang tahun selesai.');

        return Command::SUCCESS;
    }
}
