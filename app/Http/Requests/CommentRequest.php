<?php

namespace App\Http\Requests;

use App\Rules\Recaptcha;
use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize()
    {
        // Using policy for Authorization
        return true;
    }

    public function rules()
    {
        return [
            'body' => ['required', 'min:5', 'max:2000'],
            'recaptcha' => ['required', new Recaptcha()],
        ];
    }

    public function messages()
    {
        return [
            'body.required' => '請填寫留言內容',
            'body.min' => '留言內容至少 5 個字元',
            'body.max' => '留言內容至多 2000 個字元',
        ];
    }
}
