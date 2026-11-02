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
    Schema::create('child_account_requests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('couple_id')->constrained('couples')->cascadeOnDelete();
        $table->foreignId('requested_by_user_id')->constrained('users')->cascadeOnDelete();
        $table->string('name');
        $table->string('email');
        $table->date('birth_date');
        $table->string('gender', 20)->default('unspecified');
        $table->string('initial_password');
        $table->string('status', 20)->default('pending');
        $table->foreignId('created_child_user_id')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamps();
    });

    Schema::create('child_account_approvals', function (Blueprint $table) {
        $table->id();
        $table->foreignId('request_id')
            ->constrained('child_account_requests')
            ->cascadeOnDelete();

        $table->foreignId('partner_user_id')
            ->constrained('users')
            ->cascadeOnDelete();

        $table->string('status', 20)->default('pending');
        $table->timestamp('responded_at')->nullable();
        $table->timestamps();

        $table->unique(
            ['request_id', 'partner_user_id'],
            'child_acc_appr_req_partner_unique'
        );
    });

    Schema::create('child_deletion_requests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('couple_id')->constrained('couples')->cascadeOnDelete();
        $table->foreignId('child_user_id')->nullable()->constrained('users')->nullOnDelete();
        $table->string('child_name')->nullable();
        $table->foreignId('requested_by_user_id')->constrained('users')->cascadeOnDelete();
        $table->string('reason')->nullable();
        $table->string('status', 20)->default('pending');
        $table->timestamps();
    });

    Schema::create('child_deletion_approvals', function (Blueprint $table) {
        $table->id();
        $table->foreignId('deletion_request_id')
            ->constrained('child_deletion_requests')
            ->cascadeOnDelete();

        $table->foreignId('partner_user_id')
            ->constrained('users')
            ->cascadeOnDelete();

        $table->string('status', 20)->default('pending');
        $table->timestamp('responded_at')->nullable();
        $table->timestamps();

        $table->unique(
            ['deletion_request_id', 'partner_user_id'],
            'child_del_appr_req_partner_unique'
        );
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_deletion_approvals');
        Schema::dropIfExists('child_deletion_requests');
        Schema::dropIfExists('child_account_approvals');
        Schema::dropIfExists('child_account_requests');
    }
};
