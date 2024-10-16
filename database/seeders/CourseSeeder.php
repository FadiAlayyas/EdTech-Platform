<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Users
        $teachers = User::factory(20)->create(); // Creating 20 teachers

        // Create Courses for each teacher
        $teachers->each(function ($teacher) {
            Course::factory(50)->create([
                'teacher_id' => $teacher->id // Associate the course with the teacher
            ]);
        });
    }
}
