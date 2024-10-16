<?php

namespace Database\Factories;

use App\Enums\CourseStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class CourseFactory extends Factory
{
 
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(), 
            'description' => $this->faker->paragraph(), 
            'start_date' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'end_date' => $this->faker->dateTimeBetween('+1 month', '+6 months'), 
            'max_students' => $this->faker->numberBetween(10, 100),
            'category' => $this->faker->word(),
            'teacher_id' => User::factory(),
            'status' => $this->faker->randomElement(CourseStatus::cases())
        ];
    }
}
