<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubmissionLogResource extends JsonResource
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
      'assignment_id' => $this->assignment_id,
      'student_id' => $this->student_id,
      'submitted_at' => $this->submitted_at,
      'response_id' => $this->response_id,
      'status' => $this->status,
      'created_at' => $this->created_at
    ];
  }
}
