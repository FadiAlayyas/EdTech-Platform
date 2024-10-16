<?php

namespace App\Jobs;

use App\Services\SubmissionLogService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LogSubmissionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $submissionDTOs;
    protected SubmissionLogService $submissionLogService;

    public function __construct(array $submissionDTOs, SubmissionLogService $submissionLogService)
    {
        $this->submissionDTOs = $submissionDTOs;
        $this->submissionLogService = $submissionLogService;
    }

    public function handle()
    {
        try {
            // insert submissions to external service (https://jsonplaceholder.typicode.com)
            $responses = $this->submissionLogService->submitMultipleSubmissions($this->submissionDTOs);

            // Create submissionLogDTOs for logging
            $submissionLogDTOs = $this->submissionLogService->createSubmissionLogDTOs($responses);

            // insert Logs submissions to submission_logs table
            $this->submissionLogService->insert($submissionLogDTOs);
        } catch (\Exception $e) {
            Log::error('Failed to log submissions: ' . $e->getMessage());

            throw $e;
        }
    }

    public function failed(\Exception $exception)
    {
        Log::error('Job permanently failed after retries: ' . $exception->getMessage());
    }
}
