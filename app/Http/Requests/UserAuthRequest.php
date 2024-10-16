<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAuthRequest extends FormRequest
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
        return match ($this->getFunctionName()) {

            'login'  => $this->login(),
            'changePassword'  => $this->changePassword(),
            default => []
        };
    }

    public function login()
    {
        return [
            'email'  => 'required|email',
            'password' => 'required|string|min:6|max:30'
        ];
    }

    public function changePassword()
    {
        return [
            'old_password' => ['required', 'string', 'min:6', 'max:60', function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::guard('user')->user()->password)) {
                    return $fail(__('messages.currentPasswordIncorrect'));
                }
            }],
            'new_password' => ['required', 'string', 'min:6', 'max:60', 'confirmed'],
            'new_password_confirmation' => ['required', 'string', 'min:6', 'max:60'],
        ];
    }

    public function getFunctionName(): string
    {
        $action = $this->route()->getAction();
        $controllerAction = $action['controller'];
        list($controller, $method) = explode('@', $controllerAction);
        return $method;
    }
}
