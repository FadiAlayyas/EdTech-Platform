<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            // Teacher account
            $teacher = User::updateOrCreate(
                ['email' => 'teacher@edtech.com'], // Check if user already exists
                [
                    'name' => 'teacher',
                    'password' => bcrypt('123456789'),
                    'phone_number' => "+963991833806",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            $teacher->assignRole('Teacher'); // Assign Spatie role

            // Student account
            $student = User::updateOrCreate(
                ['email' => 'student@edtech.com'], // Check if user already exists
                [
                    'name' => 'student',
                    'password' => bcrypt('123456789'),
                    'phone_number' => "+963991833806",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            $student->assignRole('Student'); // Assign Spatie role

            // Admin account
            $admin = User::updateOrCreate(
                ['email' => 'admin@edtech.com'], // Check if user already exists
                [
                    'name' => 'admin',
                    'password' => bcrypt('admin123'),
                    'phone_number' => "+963991833806",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            $admin->assignRole('Admin'); // Assign Spatie role
        });
    }
}
