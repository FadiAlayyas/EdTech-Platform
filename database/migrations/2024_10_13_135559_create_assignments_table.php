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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('due_date');
            $table->date('end_date');
            $table->decimal('max_grade', 5, 2)->default(100.00); // Maximum grade a student can get
            $table->unsignedBigInteger('status')->default(1); // Status of the assignment (Assignment Status Enum)
            $table->integer('attempts_allowed')->default(1); // Number of attempts allowed for submission
            $table->boolean('is_group_assignment')->default(false); // If the assignment can be done in groups
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->timestamps();

            // Index for course_id to optimize assignment queries
            $table->index(['course_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
