<?php

namespace App\Http\Requests;

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
            'content' => ['required', 'min:5', 'max:400'],
        ];
    }

    public function messages()
    {
        return [
            'content.required' => '請填寫留言內容',
            'content.min' => '留言內容至少 5 個字元',
            'content.max' => '留言內容至多 400 個字元',
        ];
    }
}
