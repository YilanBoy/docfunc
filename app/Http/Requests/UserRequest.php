<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    // POST 參數規則
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'regex:/^[A-Za-z0-9\-\_]+$/u',
                'between:3,25',
                'unique:users,name,' . Auth::id(),
            ],
            'introduction' => ['max:80'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '請填寫會員名稱',
            'name.between' => '會員名稱必須介於 3 - 25 個字元之間。',
            'name.regex' => '會員名稱只支持英文、數字、橫槓和底線',
            'name.unique' => '會員名稱已被使用，請重新填寫',
            'name.required' => '會員名稱不能為空',
            'introduction' => '個人簡介至多 80 個字元',
        ];
    }
}
