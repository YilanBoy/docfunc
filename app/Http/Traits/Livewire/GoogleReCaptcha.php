<?php

namespace App\Http\Traits\Livewire;

use App\Rules\Recaptcha;

trait GoogleReCaptcha
{
    public string $recaptcha;

    protected function addRecaptchaRules(array $rules): array
    {
        $rules['recaptcha'] = ['required', new Recaptcha()];

        return $rules;
    }

    protected function addRecaptchaMessages(array $messages): array
    {
        $messages['recaptcha'] = '請完成驗證';

        return $messages;
    }
}
