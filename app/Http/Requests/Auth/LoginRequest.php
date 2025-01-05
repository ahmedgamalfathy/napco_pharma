<?php

namespace App\Http\Requests\Auth;

use Dotenv\Exception\ValidationException;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class LoginRequest extends FormRequest
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
            "username"=>"required|string",
            "password"=>[Password::min(3)->numbers()->letters()]
        ];
    }
    public function failedValidation(Validator $validator )
    {
      throw new ValidationException(
     response()->json([
         "message"=>$validator->errors()->first()
      ],401));
    }
}
