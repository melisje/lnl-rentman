<?php

namespace App\Http\Requests\Rentman;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'api_token'     => 'required|string',
            'webhook_token' => 'nullable|string',
            'url'           => 'required|url',
        ];
    }
}
