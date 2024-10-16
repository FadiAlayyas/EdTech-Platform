<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Course;
use App\Models\User;
use App\Services\CourseService;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Illuminate\Support\Facades\Auth;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $teacher;
    private Course $course;
    protected $courseServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PermissionsSeeder::class);
        $this->seed(RolesSeeder::class);
        
        // Creating the logged-in user and a teacher for the courses
        $this->user = User::factory()->create();
        $this->teacher = User::factory()->create();

        // Authenticating the user
        Auth::login($this->user);

        // Creating a course for reuse
        $this->course = Course::factory()->create(['teacher_id' => $this->teacher->id]);

        $this->courseServiceMock = Mockery::mock(CourseService::class);
        $this->app->instance(CourseService::class, $this->courseServiceMock);
    }

    /** @test */
    public function it_can_fetch_all_courses()
    {
        $course2 = Course::factory()->create(['teacher_id' => $this->teacher->id]);

        $this->courseServiceMock
            ->shouldReceive('getAll')
            ->once()
            ->andReturn(collect([$this->course, $course2]));

        $courses = $this->courseServiceMock->getAll();

        $this->assertCount(2, $courses);
        $this->assertTrue($courses->contains($this->course));
        $this->assertTrue($courses->contains($course2));
    }

    /** @test */
    public function it_can_create_a_course()
    {
        $data = [
            'title' => 'Test Course',
            'description' => 'This is a test course',
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'max_students' => 30,
            'category' => 'Science',
            'teacher_id' => $this->teacher->id,
            'status' => 1,
        ];

        $this->courseServiceMock
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn(Course::create($data));

        $course = $this->courseServiceMock->create($data);

        $this->assertInstanceOf(Course::class, $course);
        $this->assertDatabaseHas('courses', ['title' => 'Test Course']);
    }

    /** @test */
    public function it_can_update_a_course()
    {
        $updatedData = [
            'title' => 'Updated Course Title',
            'description' => 'Updated description',
        ];

        $this->courseServiceMock
            ->shouldReceive('update')
            ->once()
            ->with($updatedData, $this->course->id)
            ->andReturn(tap($this->course)->update($updatedData));

        $this->courseServiceMock->update($updatedData, $this->course->id);

        $this->assertDatabaseHas('courses', ['title' => 'Updated Course Title']);
    }

    /** @test */
    public function it_can_delete_a_course()
    {
        $this->courseServiceMock
            ->shouldReceive('delete')
            ->once()
            ->with($this->course->id)
            ->andReturn($this->course->delete());

        $this->courseServiceMock->delete($this->course->id);

        $this->assertDatabaseMissing('courses', ['id' => $this->course->id]);
    }

    /** @test */
    public function it_can_find_a_course_by_id()
    {
        $this->courseServiceMock
            ->shouldReceive('find')
            ->once()
            ->with($this->course->id)
            ->andReturn($this->course);

        $foundCourse = $this->courseServiceMock->find($this->course->id);

        $this->assertEquals($this->course->id, $foundCourse->id);
    }
}