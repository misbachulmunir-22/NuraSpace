<?php

namespace App\Console\Commands;

use App\Models\TimeCapsule;
use App\Notifications\TimeCapsuleOpenedNotification;
use Illuminate\Console\Command;

class CheckTimeCapsuleUnlocksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nuraspace:check-time-capsules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Periksa surat masa depan (time capsule) yang telah mencapai waktu buka dan kirim notifikasi ke penerima.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $now = now();

        $unlockedCapsules = TimeCapsule::withoutGlobalScopes()
            ->with(['sender', 'couple.partners'])
            ->where('is_notified', false)
            ->where('unlock_at', '<=', $now)
            ->get();

        $count = $unlockedCapsules->count();
        $this->info("Menemukan {$count} surat masa depan yang siap dibuka.");

        foreach ($unlockedCapsules as $capsule) {
            $recipient = $capsule->recipient;

            // If no explicit recipient, pick the other partner in the couple
            if (!$recipient && $capsule->couple) {
                $recipient = $capsule->couple->partners
                    ->firstWhere('id', '!=', $capsule->sender_id);
            }

            if ($recipient) {
                $recipient->notify(new TimeCapsuleOpenedNotification($capsule));
            }

            $capsule->update([
                'is_notified' => true,
            ]);
        }

        $this->info("Pemeriksaan surat masa depan selesai.");

        return Command::SUCCESS;
    }
}
