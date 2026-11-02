<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_text',
        'category',
        'order_index',
    ];

    public function servedCouples(): HasMany
    {
        return $this->hasMany(DailyQuestionCouple::class);
    }
}
