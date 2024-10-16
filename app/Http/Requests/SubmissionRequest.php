<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmissionRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize(): bool
  {
    // Authorize all users for now. Implement specific authorization logic as needed.
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules(): array
  {
    return match ($this->route()->getActionMethod()) {
      'create' => $this->getCreateRules(),
      'update' => $this->getUpdateRules(),
      'insert' => $this->getSubmitMultipleRules(),
      default => [],
    };
  }

  /**
   * Validation rules for creating a submission.
   *
   * @return array
   */
  public function getCreateRules(): array
  {
    return [
      'submitted_at' => 'required|date_format:Y-m-d H:i:s',
      'grade' => 'nullable|numeric|max:100', // Assuming max grade is 100
      'feedback' => 'nullable|string|max:255',
      'assignment_id' => 'required|exists:assignments,id',
      'student_id' => 'required|exists:users,id', // Assuming users table holds student IDs
    ];
  }

  /**
   * Validation rules for updating a submission.
   *
   * @return array
   */
  public function getUpdateRules(): array
  {
    return [
      'submitted_at' => 'nullable|date_format:Y-m-d H:i:s',
      'grade' => 'nullable|numeric|max:100',
      'feedback' => 'nullable|string|max:255',
      'assignment_id' => 'required|exists:assignments,id',
      'student_id' => 'required|exists:users,id',
    ];
  }

  /**
   * Validation rules for submitting multiple assignments.
   *
   * @return array
   */
  public function getSubmitMultipleRules(): array
  {
    return [
      'submissions' => 'required|array',
      'submissions.*.assignment_id' => 'required|exists:assignments,id',
      'submissions.*.student_id' => 'required|exists:users,id',
      'submissions.*.submitted_at' => 'required|date_format:Y-m-d H:i:s',
      'submissions.*.grade' => 'nullable|numeric|max:100',
      'submissions.*.feedback' => 'nullable|string|max:255',
    ];
  }
}
