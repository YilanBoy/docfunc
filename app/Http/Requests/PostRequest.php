<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public function authorize()
    {
        // Using policy for Authorization
        return true;
    }

    public function rules()
    {
        return [
            'title' => ['required', 'min:4', 'max:50'],
            'category_id' => ['required', 'numeric', 'exists:categories,id'],
            'body' => ['required', 'min:9', 'max:200000'],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => '請填寫標題',
            'title.min' => '標題至少 4 個字元',
            'title.max' => '標題至多 50 個字元',
            'category_id.required' => '請選擇文章分類',
            'category_id.numeric' => '分類資料錯誤',
            'category_id.exists' => '分類不存在',
            'body.required' => '請填寫文章內容',
            'body.min' => '文章內容至少 9 個字元',
            'body.max' => '文章內容字數已超過限制',
        ];
    }
}
