<?php

namespace App\Http\Requests\User;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "userId"=>'required|exists:users,id',
            "username"=>'required|unique:users,username',
            "name"=>'required|string',
            'phone' => 'nullable',
            'address' => 'nullable|string',
            "email"=>'required|email',
            "status"=>['required'],
            'password'=>['nullable','confirmed',Password::min(8)->letters()->numbers()],
            "roleId"=>'sometimes|exists:roles,id'
        ];
    }
    public function failedValidation(Validator $validator )
    {
            throw new HttpResponseException(response()->json([
                'message' => $validator->errors()
            ],401));
    }
}
