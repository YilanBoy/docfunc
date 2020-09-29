<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReplyRequest extends FormRequest
{
    public function authorize()
    {
        // Using policy for Authorization
        return true;
    }

    public function rules()
    {
        return [
            'content' => ['required', 'min:2', 'max:400'],
            'post_id' => ['required', 'numeric', 'exists:posts,id'],
        ];
    }

    public function messages()
    {
        return [
            'content.required' => '請填寫回覆內容',
            'content.min' => '回覆內容至少 2 個字元',
            'content.max' => '回覆內容至多 400 個字元',
            'post_id.required' => '資料錯誤',
            'post_id.numeric' => '資料錯誤',
            'post_id.exists' => '文章不存在',
        ];
    }

}
