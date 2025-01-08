<?php

namespace App\Http\Requests\Product\ProductCategory;

use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Product\ProductCategoryStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateProductCategoryRequest extends FormRequest
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
            'nameEn' => ['required', 'unique:product_category_translations,name,NULL,id,locale,en'],
            'nameAr' => ['required', 'unique:product_category_translations,name,NULL,id,locale,ar'],
            'isActive' => ['required', new Enum(ProductCategoryStatus::class)],
            'image' => ['nullable'],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors()
        ], 401));
    }
}
