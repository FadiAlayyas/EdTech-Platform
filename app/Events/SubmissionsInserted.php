<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class SubmissionsInserted
{
    use Dispatchable;

    public array $submissionDTOs;

    public function __construct(array $submissionDTOs)
    {
        $this->submissionDTOs = $submissionDTOs;
    }
}
