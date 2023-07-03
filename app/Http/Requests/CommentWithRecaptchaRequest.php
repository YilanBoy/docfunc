<?php

namespace App\Http\Requests;

use App\Rules\Recaptcha;
use Illuminate\Foundation\Http\FormRequest;

class CommentWithRecaptchaRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'body' => ['required', 'min:5', 'max:2000'],
            'recaptcha' => ['required', new Recaptcha()],
        ];
    }

    //    create a message include the recaptcha field
    public function messages(): array
    {
        return [
            'body.required' => '請填寫留言內容',
            'body.min' => '留言內容至少 5 個字元',
            'body.max' => '留言內容至多 2000 個字元',
            'recaptcha.required' => '請完成驗證',
        ];
    }
}
