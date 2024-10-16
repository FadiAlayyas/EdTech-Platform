<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Assignment;
use App\Models\User;

class SubmissionLogFactory extends Factory
{
    public function definition()
    {
        return [
            'assignment_id' => Assignment::factory(),
            'student_id' => User::factory(),
            'submitted_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'response_id' => $this->faker->randomNumber(),
            'status' => $this->faker->randomElement(['submitted', 'graded', 'pending']),
        ];
    }
}
