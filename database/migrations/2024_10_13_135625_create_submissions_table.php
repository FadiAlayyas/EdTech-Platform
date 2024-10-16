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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            // Timestamp when the assignment is submitted
            $table->timestamp('submitted_at')->nullable();
            // Optional field for grading, useful for teachers to mark submissions
            $table->decimal('grade', 5, 2)->nullable(); // Max 100.00 (supports grades like 95.75)
            // Optional field for submission feedback from the teacher
            $table->text('feedback')->nullable();
            // Foreign key for assignment, linking to the assignments table
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade');
            // Foreign key for student, linking to the users table (role is student)
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            // Timestamp for when the record was created/updated
            $table->timestamps();

            // Composite index to optimize queries based on assignment and student
            $table->index(['assignment_id', 'student_id']);
            // Additional index for submission timestamps (frequent query)
            $table->index('submitted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
