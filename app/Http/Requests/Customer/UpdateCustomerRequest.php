<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCustomerRequest extends FormRequest
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
            'customerId' => 'required',
            'name' =>['required'],
            'email' =>['required', 'email', "unique:customers,email,{$this->customerId}"],
            'phone' =>['required'],
            'address' => 'required',
            'description' => 'nullable',
        ];
    }
    public function failedValidation(Validator $validator )
    {
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors()
        ], 401));
    }
}
