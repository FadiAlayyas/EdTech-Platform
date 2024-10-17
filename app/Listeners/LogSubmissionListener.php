<?php

namespace App\Listeners;

use App\Events\SubmissionsInserted;
use App\Jobs\LogSubmissionJob;

class LogSubmissionListener
{
    public function handle(SubmissionsInserted $event)
    {
        LogSubmissionJob::dispatch($event->submissionDTOs);
    }
}
