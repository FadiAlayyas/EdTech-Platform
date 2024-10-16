<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubmissionResource extends JsonResource
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
      'submitted_at' => $this->submitted_at,
      'grade' => $this->grade,
      'feedback' => $this->feedback,
      'assignment_id' => $this->assignment_id,
      'student' => new UserResource($this->student),
      'submitted_at' => $this->submitted_at,
      'created_at' => $this->created_at
    ];
  }
}
