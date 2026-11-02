<?php

return [
    'partner_1' => [
        'name' => env('PARTNER_1_NAME', 'Misbachul Munir'),
        'email' => env('PARTNER_1_EMAIL', 'munir@nuraspace.local'),
        'gender' => 'male',
        'birth_date' => env('PARTNER_1_BIRTHDATE', '1998-08-15'),
    ],
    'partner_2' => [
        'name' => env('PARTNER_2_NAME', 'Syahra Alfi Nur Arintia'),
        'email' => env('PARTNER_2_EMAIL', 'syahra@nuraspace.local'),
        'gender' => 'female',
        'birth_date' => env('PARTNER_2_BIRTHDATE', '2000-04-20'),
    ],
    'initial_password' => env('NURASPACE_INIT_PASSWORD', 'NuraSpace2026!'),
    'couple_name' => env('NURASPACE_COUPLE_NAME', 'Munir & Syahra Space'),
    'anniversary_date' => env('NURASPACE_ANNIVERSARY', '2024-05-20'),

    /*
     * Hak akses anak (dapat disesuaikan):
     * true = diizinkan, false = dilarang
     */
    'child_permissions' => [
        'view_dashboard' => true,
        'view_calendar' => true, // hanya acara bertanda is_family_event
        'view_gallery' => true,  // hanya foto bertanda is_family_shared
        'view_countdowns' => true,
        'send_nudge' => true,
        // Fitur privat yang dilarang untuk anak:
        'journals' => false,
        'mood_tracker' => false,
        'time_capsules' => false,
        'daily_quiz' => false,
        'savings' => false,
        'family_management' => false,
    ],
];
