<?php

namespace App\Http\Requests\Rentman;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Iedereen die bij deze route komt (gecontroleerd door middleware) is geautoriseerd
        return true;
    }

    public function rules(): array
    {
        return [
            'account'       => 'required|string|unique:rm_accounts,account|max:255',
            'api_token'     => 'required|string',
            'webhook_token' => 'nullable|string',
            'url'           => 'required|url',
        ];
    }
}
