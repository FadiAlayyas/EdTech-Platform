<?php

namespace Tests\Feature;

use App\DTO\SubmissionDTO;
use App\Events\SubmissionsInserted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class SubmissionsInsertedTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_dispatches_job()
    {
        Event::fake();

        $submissionDTOs = [
            new SubmissionDTO(1, 1, now()),
            new SubmissionDTO(2, 2, now()),
        ];

        event(new SubmissionsInserted($submissionDTOs));

        Event::assertDispatched(SubmissionsInserted::class);
    }
}
