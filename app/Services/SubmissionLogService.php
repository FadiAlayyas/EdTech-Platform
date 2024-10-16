<?php

namespace App\Services;

use App\DTO\SubmissionLogDTO;
use App\HttpServices\Services\SubmissionHttpService;
use App\Repositories\SubmissionLog\SubmissionLogRepository;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelHelper;

class SubmissionLogService
{
    use ModelHelper;

    protected SubmissionLogRepository $submissionLogRepository;
    protected SubmissionHttpService $submissionHttpService;

    public function __construct(
        SubmissionLogRepository $submissionLogRepository,
        SubmissionHttpService $submissionHttpService
    ) {
        $this->submissionLogRepository = $submissionLogRepository;
        $this->submissionHttpService = $submissionHttpService;
    }

    public function getAll()
    {
        return $this->submissionLogRepository->all();
    }

    public function insert(array $submissionLogDTOs)
    {
        DB::beginTransaction();

        // Convert DTOs to array and insert them
        $submissionLogData = array_map(function (SubmissionLogDTO $dto) {
            return $dto->toArray();
        }, $submissionLogDTOs);

        $submissionLog = $this->submissionLogRepository->insert($submissionLogData);

        DB::commit();

        return $submissionLog;
    }

    public function submitMultipleSubmissions(array $submissionDTOs): array
    {
        // Log submissions to external service (this is async)
        return $this->submissionHttpService->logSubmissions($submissionDTOs);
    }

    public function createSubmissionLogDTOs(array $validatedData): array
    {
        return collect($validatedData)->map(function (array $submission) {
            return new SubmissionLogDTO(
                $submission['assignment_id'],
                $submission['student_id'],
                $submission['submitted_at'],
                $submission['id'] ?? null,
                $submission['status'] ?? 'pending'
            );
        })->toArray();
    }
}
