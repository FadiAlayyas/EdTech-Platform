<?php

namespace Tests\Integration;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AssignmentTest extends TestCase
{
    use RefreshDatabase;

    private User $teacher;
    private Course $course;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PermissionsSeeder::class);
        $this->seed(RolesSeeder::class);
        
        // Create a user and log them in
        $this->teacher = User::factory()->create();
        Auth::login($this->teacher);

        // Create a course associated with the teacher
        $this->course = Course::factory()->create(['teacher_id' => $this->teacher->id]);
    }

    /** @test */
    public function it_can_get_all_assignments()
    {
        Assignment::factory()->count(3)->create(['course_id' => $this->course->id]);

        $response = $this->get('/api/assignments');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_get_a_single_assignment()
    {
        $assignment = Assignment::factory()->create(['course_id' => $this->course->id]);

        $response = $this->get("/api/assignments/{$assignment->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => $assignment->title]);
    }

    /** @test */
    public function it_can_create_an_assignment()
    {
        $data = [
            'title' => 'New Assignment',
            'description' => 'Description of the assignment',
            'due_date' => now()->addDays(7)->toISOString(),
            'end_date' => now()->addDays(14)->toISOString(),
            'max_grade' => 100,
            'status' => 1,
            'attempts_allowed' => 3,
            'is_group_assignment' => false,
            'course_id' => $this->course->id,
        ];

        $response = $this->post('/api/assignments', $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('assignments', [
            'title' => 'New Assignment',
            'description' => 'Description of the assignment',
            'due_date' => now()->addDays(7)->toDateString(),
            'end_date' => now()->addDays(14)->toDateString(),
            'max_grade' => 100,
            'status' => 1,
            'attempts_allowed' => 3,
            'is_group_assignment' => 0,
            'course_id' => $this->course->id,
        ]);
    }

    /** @test */
    public function it_can_update_an_assignment()
    {
        $assignment = Assignment::factory()->create(['course_id' => $this->course->id]);

        $updatedData = [
            'title' => 'Updated Assignment',
            'description' => 'Updated description',
            'due_date' => now()->addDays(5)->toDateString(),
            'end_date' => now()->addDays(10)->toDateString(),
            'max_grade' => 90.00,
            'status' => 1,
            'attempts_allowed' => 2,
            'is_group_assignment' => 1,
            'course_id' => $this->course->id,
        ];

        $response = $this->put("/api/assignments/{$assignment->id}", $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('assignments', [
            'title' => 'Updated Assignment',
            'description' => 'Updated description',
            'due_date' => $updatedData['due_date'],
            'end_date' => $updatedData['end_date'],
            'max_grade' => '90.00',
            'status' => $updatedData['status'],
            'attempts_allowed' => $updatedData['attempts_allowed'],
            'is_group_assignment' => $updatedData['is_group_assignment'],
            'course_id' => $updatedData['course_id'],
        ]);
    }

    /** @test */
    public function it_can_delete_an_assignment()
    {
        $assignment = Assignment::factory()->create(['course_id' => $this->course->id]);

        $this->assertDatabaseHas('assignments', [
            'id' => $assignment->id,
        ]);

        $response = $this->delete("/api/assignments/{$assignment->id}");

        $response->assertStatus(200);
        
        $this->assertDatabaseMissing('assignments', [
            'id' => $assignment->id,
        ]);
    }
}
