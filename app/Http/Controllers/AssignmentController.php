<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignmentRequest;
use App\Http\Resources\AssignmentResource;
use App\Services\AssignmentService;

class AssignmentController extends Controller
{
    public function __construct(private AssignmentService $assignmentService)
    {
    }

    public function getAll()
    {
        $assignments = $this->assignmentService->getAll();
        
        return $this->successResponse(
            $this->resource($assignments, AssignmentResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function find($assignmentId)
    {
        $assignment = $this->assignmentService->find($assignmentId);

        return $this->successResponse(
            $this->resource($assignment, AssignmentResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function create(AssignmentRequest $request)
    {
        $validatedData = $request->validated();

        $assignment = $this->assignmentService->create($validatedData);

        return $this->successResponse(
            $this->resource($assignment, AssignmentResource::class),
            'dataAddedSuccessfully'
        );
    }

    public function update(AssignmentRequest $request, $assignmentId)
    {
        $validatedData = $request->validated();

        $this->assignmentService->update($validatedData, $assignmentId);

        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }

    public function delete($assignmentId)
    {
        $this->assignmentService->delete($assignmentId);

        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }
}
