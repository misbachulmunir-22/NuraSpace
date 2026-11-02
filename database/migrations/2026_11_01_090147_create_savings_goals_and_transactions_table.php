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
        Schema::create('savings_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('couple_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedBigInteger('target_amount'); // stored as integer Rupiah
            $table->unsignedBigInteger('current_amount')->default(0);
            $table->date('target_date')->nullable();
            $table->text('description')->nullable();
            $table->string('color', 30)->default('pink');
            $table->string('icon', 10)->default('💰');
            $table->string('status', 20)->default('active'); // active, achieved, cancelled
            $table->timestamp('achieved_at')->nullable();
            $table->timestamps();

            $table->index(['couple_id', 'status']);
        });

        Schema::create('savings_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('savings_goal_id')->constrained('savings_goals')->cascadeOnDelete();
            $table->foreignId('couple_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('amount'); // deposit amount in Rupiah
            $table->date('transaction_date');
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->index(['savings_goal_id', 'transaction_date']);
            $table->index('couple_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('savings_transactions');
        Schema::dropIfExists('savings_goals');
    }
};
