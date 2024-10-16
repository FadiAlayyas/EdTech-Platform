<?php

namespace App\Http\Resources;

use App\Enums\CourseStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request)
  {
    return [
      'id' => $this->id,
      'title' => $this->title,
      'description' => $this->description,
      'start_date' => $this->start_date,
      'end_date' => $this->end_date,
      'max_students' => $this->max_students,
      'category' => $this->category,
      'teacher' => new UserResource($this->teacher),
      'status' => CourseStatus::fromValue($this->status),
      'assignments' => AssignmentResource::collection($this->assignments),
      'created_at' => $this->created_at
    ];
  }
}
