<?php

namespace App\Http\Requests;

use App\Enums\AssignmentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignmentRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return match ($this->route()->getActionMethod()) {
      'create' => $this->getCreateRules(),
      'update' => $this->getUpdateRules(),
      default => []
    };
  }

  public function getCreateRules()
  {
    return [
      'title' => 'required|string|max:255',
      'description' => 'nullable|string',
      'due_date' => 'required|date|after:today',
      'end_date' => 'nullable|date|after:due_date',
      'max_grade' => 'required|numeric|min:0',
      'status' => ['nullable', Rule::in(AssignmentStatus::selectValues())],
      'attempts_allowed' => 'required|integer|min:1',
      'is_group_assignment' => 'required|boolean',
      'course_id' => 'required|exists:courses,id',
    ];
  }

  public function getUpdateRules()
  {
    return [
      'title' => 'sometimes|required|string|max:255',
      'description' => 'sometimes|nullable|string',
      'due_date' => 'sometimes|required|date|after:today',
      'end_date' => 'sometimes|nullable|date|after:due_date',
      'max_grade' => 'sometimes|required|numeric|min:0',
      'status' => ['nullable', Rule::in(AssignmentStatus::selectValues())],
      'attempts_allowed' => 'sometimes|required|integer|min:1',
      'is_group_assignment' => 'sometimes|required|boolean',
      'course_id' => 'sometimes|required|exists:courses,id',
    ];
  }
}
