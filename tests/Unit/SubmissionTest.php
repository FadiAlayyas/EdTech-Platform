<?php

namespace Tests\Unit;

use App\Models\Submission;
use App\Models\Assignment;
use App\Services\SubmissionService;
use App\HttpServices\Services\SubmissionHttpService;
use App\Models\User;
use App\Services\SubmissionLogService;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\RolesSeeder;

use Tests\TestCase;
use Mockery;

class SubmissionTest extends TestCase
{
    protected $submissionServiceMock;
    protected $submissionHttpServiceMock;
    protected $submissionLogServiceMock;
    private Submission $submission;
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PermissionsSeeder::class);
        $this->seed(RolesSeeder::class);

        // Create mocks for the dependencies
        $this->submissionServiceMock = Mockery::mock(SubmissionService::class);
        $this->submissionHttpServiceMock = Mockery::mock(SubmissionHttpService::class);
        $this->submissionLogServiceMock = Mockery::mock(SubmissionLogService::class);


        $assignment = Assignment::factory()->create();
        $student = User::factory()->create();
        $this->submission = Submission::factory()->create(['student_id' => $student->id, 'assignment_id' => $assignment->id]);
    }

    public function testGetAll()
    {
        // Arrange
        $submissions = collect([
            ['id' => 1, 'assignment_id' => 1, 'student_id' => 1],
            ['id' => 2, 'assignment_id' => 2, 'student_id' => 2],
        ]);

        $this->submissionServiceMock
            ->shouldReceive('getAll')
            ->once()
            ->andReturn($submissions);

        // Act
        $result = $this->submissionServiceMock->getAll();

        // Assert
        $this->assertEquals($submissions, $result);
    }

    public function testFind()
    {
        $this->submissionServiceMock
            ->shouldReceive('find')
            ->once()
            ->with($this->submission->id)
            ->andReturn($this->submission);

        $result = $this->submissionServiceMock->find($this->submission->id);

        $this->assertEquals($this->submission->id, $result->id);
    }

    public function testCreate()
    {
        // Arrange
        $assignment = Assignment::factory()->create();
        $student = User::factory()->create();

        $validatedData = [
            'submitted_at' => '2024-01-01 12:00:00',
            'assignment_id' => $assignment->id,
            'student_id' => $student->id,
        ];

        $this->submissionServiceMock
            ->shouldReceive('create')
            ->once()
            ->with($validatedData)
            ->andReturnTrue();

        $submission = $this->submissionServiceMock->create($validatedData);

        $this->assertTrue($submission);
    }

    public function testUpdate()
    {
        $validatedData = [
            'submitted_at' => '2024-01-01 12:00:00',
            'grade' => 90,
            'feedback' => 'Good job',
            'assignment_id' => $this->submission->assignment_id,
            'student_id' => $this->submission->student_id,
        ];

        $this->submissionServiceMock
            ->shouldReceive('update')
            ->once()
            ->with($validatedData, $this->submission->id)
            ->andReturnTrue();

        $result = $this->submissionServiceMock->update($validatedData, $this->submission->id);

        $this->assertTrue($result);
    }

    public function testDelete()
    {
        $this->submissionServiceMock
            ->shouldReceive('delete')
            ->once()
            ->with($this->submission->id)
            ->andReturnTrue();

        $result = $this->submissionServiceMock->delete($this->submission->id);

        $this->assertTrue($result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
