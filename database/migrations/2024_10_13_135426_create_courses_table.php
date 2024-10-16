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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('start_date')->nullable(); // Optional start date
            $table->date('end_date')->nullable(); // Optional end date
            $table->integer('max_students')->nullable(); // Maximum number of students allowed in the course
            $table->string('category')->nullable(); // Category of the course for filtering and searching
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('status')->default(1); // Manage course state (Course Status Enum)
            $table->timestamps();

            // Indexes for optimization
            $table->index(['teacher_id', 'status', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
