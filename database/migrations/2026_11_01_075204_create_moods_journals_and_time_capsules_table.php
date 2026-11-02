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
        Schema::create('moods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('couple_id')->constrained('couples')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('mood_type', 30); // happy, sad, tired, angry, needy, excited
            $table->string('note')->nullable();
            $table->date('logged_date');
            $table->timestamps();

            $table->unique(['couple_id', 'user_id', 'logged_date']);
        });

        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('couple_id')->constrained('couples')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('content');
            $table->string('visibility', 20)->default('shared'); // private, shared
            $table->string('mood_tag', 50)->nullable();
            $table->date('entry_date');
            $table->timestamps();

            $table->index(['couple_id', 'visibility', 'entry_date']);
        });

        Schema::create('time_capsules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('couple_id')->constrained('couples')->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('recipient_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('content');
            $table->dateTime('unlock_at');
            $table->boolean('is_notified')->default(false);
            $table->dateTime('opened_at')->nullable();
            $table->timestamps();

            $table->index(['couple_id', 'unlock_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_capsules');
        Schema::dropIfExists('journals');
        Schema::dropIfExists('moods');
    }
};
