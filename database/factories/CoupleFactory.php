<?php

namespace Database\Factories;

use App\Models\Couple;
use Illuminate\Database\Eloquent\Factories\Factory;

class CoupleFactory extends Factory
{
    protected $model = Couple::class;

    public function definition(): array
    {
        return [
            'name' => 'Munir & Syahra Space',
            'anniversary_date' => '2024-05-20',
        ];
    }
}
