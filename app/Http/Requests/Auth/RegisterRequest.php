<?php

namespace App\Http\Requests\Auth;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class RegisterRequest extends FormRequest
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
            'name'=>"string|required",
            'email'=>"string|required|email|unique:users,email",
            'password'=>Password::min(5)->numbers()->letters(),
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