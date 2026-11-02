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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('couple_id')->constrained('couples')->cascadeOnDelete();
            $table->foreignId('created_by_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category', 30)->default('other'); // date_night, family, work_exam, anniversary, birthday, other
            $table->string('color', 30)->default('#f43f5e');
            $table->dateTime('start_at');
            $table->dateTime('end_at')->nullable();
            $table->boolean('is_all_day')->default(false);
            $table->boolean('is_family_event')->default(true);
            $table->timestamps();

            $table->index(['couple_id', 'start_at']);
        });

        Schema::create('countdowns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('couple_id')->constrained('couples')->cascadeOnDelete();
            $table->foreignId('created_by_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title', 150);
            $table->dateTime('target_date');
            $table->string('icon_emoji', 20)->default('❤️');
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();

            $table->index(['couple_id', 'target_date']);
        });

        Schema::create('reminder_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('couple_id')->constrained('couples')->cascadeOnDelete();
            $table->string('reminder_type', 50); // anniversary, birthday
            $table->unsignedBigInteger('target_id')->nullable();
            $table->integer('target_year');
            $table->string('stage', 20); // h_minus_7, h_minus_1, day_of
            $table->dateTime('sent_at');
            $table->timestamps();

            $table->unique(['couple_id', 'reminder_type', 'target_id', 'target_year', 'stage'], 'reminder_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminder_logs');
        Schema::dropIfExists('countdowns');
        Schema::dropIfExists('events');
    }
};
