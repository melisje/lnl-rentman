<?php

namespace App\Http\Requests\Rentman;

use Illuminate\Foundation\Http\FormRequest;

class UpdateApiTokenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // We need to check if the user has or admin role or the apitoken role.
        // See also the AppServiceProvider
        // For the time being, this is not yet implemented
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'account' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'api_token' => 'max:500',
            'webhook_token' => 'max:500',
        ];
    }
}
