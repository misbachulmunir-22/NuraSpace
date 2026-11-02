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
        Schema::create('memories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('couple_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('memory_date');
            $table->string('location')->nullable();
            $table->boolean('is_milestone')->default(false);
            $table->timestamps();

            $table->index(['couple_id', 'memory_date']);
            $table->index(['couple_id', 'is_milestone']);
        });

        Schema::create('memory_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memory_id')->constrained()->cascadeOnDelete();
            $table->string('file_path');
            $table->string('media_type')->default('image'); // image, video
            $table->string('caption')->nullable();
            $table->integer('order_index')->default(0);
            $table->timestamps();

            $table->index('memory_id');
        });

        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('couple_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category')->default('other'); // travel, item, date_idea, milestone_goal, other
            $table->unsignedBigInteger('estimated_cost')->nullable(); // in Rupiah (stored as integer without decimal)
            $table->date('target_date')->nullable();
            $table->string('status')->default('pending'); // pending, in_progress, achieved, cancelled
            $table->timestamp('achieved_at')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamps();

            $table->index(['couple_id', 'status']);
            $table->index(['couple_id', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('memory_media');
        Schema::dropIfExists('memories');
    }
};
