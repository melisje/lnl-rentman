<?php

namespace App\Http\Requests\Rentman;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomFieldRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Haal het ID van het CustomField op uit de route
        // Laravel haalt dit automatisch uit admin/rentman/customfield/{custom_field}
        $customFieldId = $this->route('customField');

        return [
            'rm_id' => [
                'required',
                'integer',
                Rule::unique('rm_customfields')
                    ->where(function ($query) {
                        return $query->where('account', $this->account);
                    })
                    ->ignore($customFieldId), // Dit is de cruciale toevoeging
            ],

            'account'    => 'required|string|exists:rm_accounts,account',
            'name'       => 'required|string|max:255',
            'belongs_to' => 'nullable|string|max:255',
            'type'       => 'nullable|string|max:255',
            'hidden'     => 'nullable|string|max:255',
            'private'    => 'nullable|string|max:255',
            'mandatory'  => 'nullable|boolean',
        ];
    }
}
