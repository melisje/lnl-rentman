<?php

namespace App\Http\Requests\Rentman;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomFieldRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 1. Unieke combinatie van rm_id en account
            'rm_id' => [
                'required',
                'integer',
                Rule::unique('rm_customfields')->where(function ($query) {
                    return $query->where('account', $this->account);
                }),
            ],

            // 2. Account moet bestaan in rm_accounts
            'account' => 'required|string|exists:rm_accounts,account',

            // 3. Verplichte velden (volgens je DB schema)
            'name' => 'required|string|max:255',

            // 4. Optionele velden (moeten in rules staan om door validated() te komen!)
            'belongs_to' => 'nullable|string|max:255',
            'type'       => 'nullable|string|max:255',

            // In je DB zijn hidden en private varchars, mandatory is tinyint (boolean)
            'hidden'     => 'nullable|string|max:255',
            'private'    => 'nullable|string|max:255',
            'mandatory'  => 'nullable|boolean',
        ];
    }
}
