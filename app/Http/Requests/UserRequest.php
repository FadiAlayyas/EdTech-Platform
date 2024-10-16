<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
      'profileUpdate' => $this->getProfileUpdateRules(),
      'updateStatus' => $this->getUpdateStatusRules(),
    };
  }

  public function getCreateRules()
  {
    return [
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email|max:255',
      'password' => 'required|string|min:6|max:60',
      'email_verified_at' => 'nullable|date',
      'phone_number' => 'nullable|string|max:15',
      'is_active' => 'nullable|boolean',
      'role' => ['required', Rule::in(UserRole::selectNames())]
    ];
  }

  public function getUpdateRules()
  {
    $userId = $this->route('id');
    return [
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email,' . $userId . '|max:255',
      'password' => 'nullable|string|min:6|max:60',
      'email_verified_at' => 'nullable|date',
      'phone_number' => 'nullable|string|max:15',
      'is_active' => 'nullable|boolean',
      'role' => ['nullable', Rule::in(UserRole::selectNames())]
    ];
  }

  public function getProfileUpdateRules()
  {
    $userId = Auth::guard('user')->user()->id;
    return [
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email,' . $userId . '|max:255',
      'phone_number' => 'nullable|string|max:15',
      'old_password' => ['nullable', 'string', 'min:6', 'max:60', function ($attribute, $value, $fail) {
        if (!Hash::check($value, Auth::guard('user')->user()->password)) {
          return $fail(__('messages.currentPasswordIncorrect'));
        }
      }],
      'new_password' => ['nullable', 'string', 'min:6', 'max:60', 'confirmed'],
      'new_password_confirmation' => ['nullable', 'string', 'min:6', 'max:60'],
    ];
  }

  public function getUpdateStatusRules()
  {
    return [
      'status' => 'required|boolean',
    ];
  }
}
