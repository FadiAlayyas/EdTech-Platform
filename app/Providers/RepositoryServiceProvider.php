<?php


namespace App\Providers;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Submission;
use App\Models\SubmissionLog;
use App\Repositories\Assignment\AssignmentRepository;
use App\Repositories\Assignment\EloquentAssignmentRepository;
use App\Repositories\Course\CourseRepository;
use App\Repositories\Course\EloquentCourseRepository;
use App\Repositories\Submission\EloquentSubmissionRepository;
use App\Repositories\Submission\SubmissionRepository;
use App\Repositories\SubmissionLog\EloquentSubmissionLogRepository;
use App\Repositories\SubmissionLog\SubmissionLogRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        $this->app->bind(CourseRepository::class, function () {
            return new EloquentCourseRepository(new Course());
        });

        $this->app->bind(SubmissionRepository::class, function () {
            return new EloquentSubmissionRepository(new Submission());
        });

        $this->app->bind(AssignmentRepository::class, function () {
            return new EloquentAssignmentRepository(new Assignment());
        });

        $this->app->bind(SubmissionLogRepository::class, function () {
            return new EloquentSubmissionLogRepository(new SubmissionLog());
        });
    }
}
