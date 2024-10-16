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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable(); // For email verification
            $table->string('phone_number')->nullable(); // Optional phone number for verification or notification
            $table->boolean('is_active')->default(true); // To manage active/inactive users
            $table->rememberToken(); // For "remember me" functionality in authentication
            $table->timestamps();

            // Indexes for optimization
            $table->index(['email', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
