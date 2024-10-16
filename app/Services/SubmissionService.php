<?php

namespace App\Services;

use App\DTO\SubmissionDTO;
use App\HttpServices\Services\SubmissionHttpService;
use App\Jobs\LogSubmissionJob;
use Illuminate\Support\Facades\DB;
use App\Traits\ModelHelper;
use App\Repositories\Submission\SubmissionRepository;
use App\Services\SubmissionLogService;

class SubmissionService
{
    use ModelHelper;

    protected SubmissionRepository $submissionRepository;
    protected SubmissionHttpService $submissionHttpService;
    protected SubmissionLogService $submissionLogService;

    public function __construct(
        SubmissionHttpService $submissionHttpService,
        SubmissionRepository $submissionRepository,
        SubmissionLogService $submissionLogService
    ) {
        $this->submissionHttpService = $submissionHttpService;
        $this->submissionRepository = $submissionRepository;
        $this->submissionLogService = $submissionLogService;
    }

    public function getAll()
    {
        return $this->submissionRepository->all(['*'], ['student']);
    }

    public function find(int $submissionId)
    {
        return $this->submissionRepository->findOrFail($submissionId);
    }

    public function create(array $validatedData)
    {
        DB::beginTransaction();

        $this->submissionRepository->create($validatedData);

        DB::commit();

        return true;
    }

    public function update(array $validatedData, int $submissionId)
    {
        DB::beginTransaction();

        $this->submissionRepository->update($validatedData, ['id' => $submissionId]);

        DB::commit();

        return true;
    }

    public function delete(int $submissionId)
    {
        DB::beginTransaction();

        $this->submissionRepository->destroy(['id' => $submissionId]);

        DB::commit();

        return true;
    }

    public function insert(array $validatedData)
    {
        DB::beginTransaction();

        // Insert submissions into the submissions table
        $this->submissionRepository->insert($validatedData['submissions']);

        // Create DTOs from validated data for logging
        $submissionDTOs = $this->createSubmissionDTOs($validatedData['submissions']);

        // Dispatch a job to log the submissions asynchronously
        LogSubmissionJob::dispatch($submissionDTOs, $this->submissionLogService);

        DB::commit();

        return $validatedData['submissions'];
    }

    public function createSubmissionDTOs(array $validatedData): array
    {
        return collect($validatedData)->map(function ($submission) {
            return new SubmissionDTO(
                $submission['assignment_id'],
                $submission['student_id'],
                $submission['submitted_at']
            );
        })->toArray();
    }
}
