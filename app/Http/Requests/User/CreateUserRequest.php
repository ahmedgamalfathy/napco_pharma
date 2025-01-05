<?php

namespace App\Http\Requests\User;

use App\Enums\User\UserStatus;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class CreateUserRequest extends FormRequest
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
            "name"=>'required|string',
            "username"=>'required|string',
            "phone"=>'nullable|string',
            "address"=>'nullable|string',
            "avatar"=>'nullable|image|mimes:png,jpg,webg,jpeg,gif',
            "email"=>'nullable|email',
            "status"=>['required',new Enum(UserStatus::class)],
            'password'=>['required','confirmed',Password::min(8)->letters()->numbers()],
            "roleId"=>'required|exists:roles,id'
        ];
    }
    public function failedValidation(Validator $validator )
    {
     throw new HttpResponseException(
         response()->json([
            "message"=>$validator->errors()->first()
         ],401));
    }
}
