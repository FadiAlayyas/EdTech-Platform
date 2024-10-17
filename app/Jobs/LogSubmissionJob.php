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

    public function __construct(array $submissionDTOs)
    {
        $this->submissionDTOs = $submissionDTOs;
        $this->submissionLogService = app(SubmissionLogService::class);
    }

    public function handle()
    {
        try {
            // Insert submissions to external service
            $responses = $this->submissionLogService->submitMultipleSubmissions($this->submissionDTOs);

            // Create submissionLogDTOs for logging
            $submissionLogDTOs = $this->submissionLogService->createSubmissionLogDTOs($responses);

            // Insert logs into submission_logs table
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
