<?php

namespace App\DTO;

class SubmissionDTO
{
    public function __construct(
        public int $assignment_id,
        public int $student_id,
        public string $submitted_at
    ) {}

    public function toArray(): array
    {
        return [
            'assignment_id' => $this->assignment_id,
            'student_id' => $this->student_id,
            'submitted_at' => $this->submitted_at,
        ];
    }
}
