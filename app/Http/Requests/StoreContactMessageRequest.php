<?php

namespace App\Http\Requests;

use App\Rules\RecaptchaV3;
use Illuminate\Foundation\Http\FormRequest;

class StoreContactMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'message' => ['required', 'string', 'min:5', 'max:5000'],
            'website' => ['nullable', 'string'],
            'recaptcha_token' => ['nullable', 'string', new RecaptchaV3('contact')],
        ];
    }

    /**
     * Bots fill the hidden "website" field; humans never see it.
     */
    public function isHoneypotTripped(): bool
    {
        return filled($this->input('website'));
    }
}
