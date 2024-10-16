<?php

namespace App\Http\Controllers;

use App\DTO\SubmissionDTO;
use App\Http\Requests\SubmissionRequest;
use App\Http\Resources\SubmissionResource;
use App\Services\SubmissionService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function __construct(private SubmissionService $submissionService) {}

    public function getAll()
    {
        $submissions = $this->submissionService->getAll();
        return $this->successResponse(
            $this->resource($submissions, SubmissionResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function find($submissionId)
    {
        $submission = $this->submissionService->find($submissionId);

        return $this->successResponse(
            $this->resource($submission, SubmissionResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function create(SubmissionRequest $request)
    {
        $validatedData = $request->validated();
        $this->submissionService->create($validatedData);

        return $this->successResponse(
            null,
            'dataAddedSuccessfully'
        );
    }

    public function update(SubmissionRequest $request, $submissionId)
    {
        $validatedData = $request->validated();
        $this->submissionService->update($validatedData, $submissionId);

        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }

    public function delete($submissionId)
    {
        $this->submissionService->delete($submissionId);

        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }

    public function insert(SubmissionRequest $request)
    {
        $validatedData = $request->validated();

        $result = $this->submissionService->insert($validatedData);

        return $this->successResponse(
            $result,
            'dataInsertedSuccessfully'
        );
    }
}
