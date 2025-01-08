<?php

namespace App\Http\Requests\Faq;

use App\Enums\Faq\FaqStatus;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateFaqRequest extends FormRequest
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
            'faqId' => 'required',
            'questionEn' => ['required', Rule::unique('faq_translations', 'question')
            ->ignore($this->faqId, 'faq_id')->where('locale', 'en')],
            'questionAr' => ['required', Rule::unique('faq_translations', 'question')
            ->ignore($this->faqId, 'faq_id')->where('locale', 'ar')],
            'answerEn' => ['required'],
            'answerAr' => ['required'],
            'isPublished' => ['required', new Enum(FaqStatus::class)],
            'order' => ['required']
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors()
        ], 401));
    }
}
