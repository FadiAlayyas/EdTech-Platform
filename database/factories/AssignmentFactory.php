<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Course;
use App\Enums\AssignmentStatus;

class AssignmentFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'due_date' => $this->faker->dateTimeBetween('now', '+30 days'),
            'end_date' => $this->faker->dateTimeBetween('now', '+60 days'),
            'max_grade' => $this->faker->numberBetween(1, 100),
            'status' => $this->faker->randomElement(AssignmentStatus::cases()), // Use enum values
            'attempts_allowed' => $this->faker->numberBetween(1, 5),
            'is_group_assignment' => $this->faker->boolean(),
            'course_id' => Course::factory(),
        ];
    }
}
