<?php

namespace Database\Seeders;

use App\Models\Couple;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $coupleConfig = config('nuraspace');

        $couple = Couple::firstOrCreate(
            ['id' => 1],
            [
                'name' => $coupleConfig['couple_name'],
                'anniversary_date' => $coupleConfig['anniversary_date'],
            ]
        );

        $initialPassword = Hash::make($coupleConfig['initial_password']);

        // Partner 1: Misbachul Munir
        User::updateOrCreate(
            ['email' => $coupleConfig['partner_1']['email']],
            [
                'couple_id' => $couple->id,
                'name' => $coupleConfig['partner_1']['name'],
                'password' => $initialPassword,
                'role' => 'partner',
                'gender' => $coupleConfig['partner_1']['gender'],
                'birth_date' => $coupleConfig['partner_1']['birth_date'],
                'must_change_password' => true,
                'is_active' => true,
            ]
        );

        // Partner 2: Syahra Alfi Nur Arintia
        User::updateOrCreate(
            ['email' => $coupleConfig['partner_2']['email']],
            [
                'couple_id' => $couple->id,
                'name' => $coupleConfig['partner_2']['name'],
                'password' => $initialPassword,
                'role' => 'partner',
                'gender' => $coupleConfig['partner_2']['gender'],
                'birth_date' => $coupleConfig['partner_2']['birth_date'],
                'must_change_password' => true,
                'is_active' => true,
            ]
        );

        // Seed daily questions
        $this->call(DailyQuestionSeeder::class);
    }
}
