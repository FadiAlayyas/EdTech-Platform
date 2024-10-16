<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Assignment;
use App\Models\User;
use App\Models\Submission;

class SubmissionFactory extends Factory
{
    protected $model = Submission::class;

    public function definition()
    {
        return [
            'submitted_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'grade' => $this->faker->numberBetween(0, 100),
            'feedback' => $this->faker->paragraph(),
            'assignment_id' => Assignment::factory(),
            'student_id' => User::factory()
        ];
    }
}
