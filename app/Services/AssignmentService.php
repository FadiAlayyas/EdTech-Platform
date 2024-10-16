<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Traits\ModelHelper;
use App\Repositories\Assignment\AssignmentRepository;

class AssignmentService
{
    use ModelHelper;

    protected AssignmentRepository $assignmentRepository;

    public function __construct(AssignmentRepository $assignmentRepository)
    {
        $this->assignmentRepository = $assignmentRepository;
    }

    public function getAll()
    {
        return $this->assignmentRepository->all(['*'], ['course','submissions']);
    }

    public function find($assignmentId)
    {
        return $this->assignmentRepository->findOrFail($assignmentId);
    }

    public function create($validatedData)
    {
        DB::beginTransaction();

        $assignment = $this->assignmentRepository->create($validatedData);

        DB::commit();

        return $assignment;
    }

    public function update($validatedData, $assignmentId)
    {
        DB::beginTransaction();

        $this->assignmentRepository->update($validatedData, ['id' => $assignmentId]);

        DB::commit();

        return true;
    }

    public function delete($assignmentId)
    {
        DB::beginTransaction();

        $this->assignmentRepository->destroy(['id' => $assignmentId]);

        DB::commit();

        return true;
    }
}
