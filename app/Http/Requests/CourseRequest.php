<?php

namespace App\Http\Requests;

use App\Enums\CourseStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
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
      'start_date' => 'required|date',
      'end_date' => 'nullable|date',
      'max_students' => 'required|integer|min:1',
      'category' => 'required|string|max:100',
      'teacher_id' => 'required|exists:users,id',
      'status' => ['nullable', Rule::in(CourseStatus::selectValues())],
    ];
  }

  public function getUpdateRules()
  {
    return [
      'title' => 'sometimes|required|string|max:255',
      'description' => 'sometimes|nullable|string',
      'start_date' => 'sometimes|required|date',
      'end_date' => 'sometimes|nullable|date',
      'max_students' => 'sometimes|required|integer|min:1',
      'category' => 'sometimes|required|string|max:100',
      'teacher_id' => 'sometimes|required|exists:users,id',
      'status' => ['sometimes', Rule::in(CourseStatus::selectValues())],
    ];
  }
}
