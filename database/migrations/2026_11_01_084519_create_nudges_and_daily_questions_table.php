<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nudges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('couple_id')->constrained('couples')->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('recipient_id')->constrained('users')->cascadeOnDelete();
            $table->string('nudge_type', 20); // hug, kiss, miss_you
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['couple_id', 'recipient_id', 'read_at']);
        });

        Schema::create('daily_questions', function (Blueprint $table) {
            $table->id();
            $table->text('question_text');
            $table->string('category', 50)->default('romantic');
            $table->unsignedInteger('order_index')->default(0);
            $table->timestamps();
        });

        Schema::create('daily_question_couples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('couple_id')->constrained('couples')->cascadeOnDelete();
            $table->foreignId('daily_question_id')->constrained('daily_questions')->cascadeOnDelete();
            $table->date('served_date');
            $table->timestamps();

            $table->unique(['couple_id', 'served_date']);
        });

        Schema::create('daily_question_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('couple_id')->constrained('couples')->cascadeOnDelete();
            $table->foreignId('daily_question_couple_id')->constrained('daily_question_couples')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('answer_text');
            $table->timestamps();

            $table->unique(['daily_question_couple_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_question_answers');
        Schema::dropIfExists('daily_question_couples');
        Schema::dropIfExists('daily_questions');
        Schema::dropIfExists('nudges');
    }
};
