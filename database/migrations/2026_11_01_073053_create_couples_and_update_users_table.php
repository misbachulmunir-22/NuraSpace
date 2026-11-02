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
        Schema::create('couples', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Munir & Syahra Space');
            $table->date('anniversary_date')->nullable();
            $table->string('avatar_cover_path')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('couple_id')->nullable()->after('id')->constrained('couples')->cascadeOnDelete();
            $table->string('role', 20)->default('child')->after('password'); // 'partner' or 'child'
            $table->string('gender', 20)->default('unspecified')->after('role'); // 'male', 'female', 'unspecified'
            $table->date('birth_date')->nullable()->after('gender');
            $table->string('avatar_path')->nullable()->after('birth_date');
            $table->boolean('must_change_password')->default(true)->after('avatar_path');
            $table->boolean('is_active')->default(true)->after('must_change_password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['couple_id']);
            $table->dropColumn([
                'couple_id',
                'role',
                'gender',
                'birth_date',
                'avatar_path',
                'must_change_password',
                'is_active',
            ]);
        });

        Schema::dropIfExists('couples');
    }
};
