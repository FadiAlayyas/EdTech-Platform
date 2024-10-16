<?php

namespace App\DTO;

class SubmissionLogDTO
{
    public function __construct(
        public int $assignment_id,
        public int $student_id,
        public string $submitted_at,
        public ?int $response_id = null,
        public ?string $status = 'pending'
    ) {}

    public function toArray(): array
    {
        return [
            'assignment_id' => $this->assignment_id,
            'student_id' => $this->student_id,
            'submitted_at' => $this->submitted_at,
            'response_id' => $this->response_id,
            'status' => $this->status
        ];
    }
}
