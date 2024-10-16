<?php

namespace App\Http\Resources;

use App\Enums\AssignmentStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
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
      'due_date' => $this->due_date,
      'end_date' => $this->end_date,
      'max_grade' => $this->max_grade,
      'status' => AssignmentStatus::fromValue($this->status),
      'attempts_allowed' => $this->attempts_allowed,
      'is_group_assignment' => $this->is_group_assignment,
      'course_id' => $this->course_id,
      'submissions'  =>  SubmissionResource::collection($this->submissions),
      'created_at' => $this->created_at
    ];
  }
}
