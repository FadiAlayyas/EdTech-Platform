<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Http\Resources\CourseResource;
use App\Services\CourseService;

class CourseController extends Controller
{
    public function __construct(private CourseService $courseService)
    {
    }

    public function getAll()
    {
        $courses = $this->courseService->getAll();
        return $this->successResponse(
            $this->resource($courses, CourseResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function find($courseId)
    {
        $course = $this->courseService->find($courseId);

        return $this->successResponse(
            $this->resource($course, CourseResource::class),
            'dataFetchedSuccessfully'
        );
    }

    public function create(CourseRequest $request)
    {
        $validatedData = $request->validated();
        $course = $this->courseService->create($validatedData);

        return $this->successResponse(
            $this->resource($course, CourseResource::class),
            'dataAddedSuccessfully'
        );
    }

    public function update(CourseRequest $request, $courseId)
    {
        $validatedData = $request->validated();
        $this->courseService->update($validatedData, $courseId);

        return $this->successResponse(
            null,
            'dataUpdatedSuccessfully'
        );
    }

    public function delete($courseId)
    {
        $this->courseService->delete($courseId);

        return $this->successResponse(
            null,
            'dataDeletedSuccessfully'
        );
    }
}
