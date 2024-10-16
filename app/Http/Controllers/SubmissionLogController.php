<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubmissionLogResource;
use App\Services\SubmissionLogService;

class SubmissionLogController extends Controller
{
    public function __construct(private SubmissionLogService $submission_logService)
    {
    }

    public function getAll()
    {
        $submission_logs = $this->submission_logService->getAll();
        return $this->successResponse(
            $this->resource($submission_logs, SubmissionLogResource::class),
            'dataFetchedSuccessfully'
        );
    }
}
