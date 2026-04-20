<?php

namespace App\Models\Rentman;

use App\Scopes\AccountScope;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'rm_statuses';

    protected $guarded =
    [
        'id'
    ];

    /**
     * De casts die moeten worden toegepast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime', // Optioneel: direct formatteren
            'modified_at' => 'datetime', // Optioneel: direct formatteren
            'created' => 'datetime', // Dit cast de kolom naar Carbon
            'modified' => 'datetime', // Dit cast de kolom naar Carbon
        ];
    }

    /**
     * Boot the model and apply the global scope.
     */
    protected static function booted()
    {
        // Filter all queries automatically based on the current account.
        // If you do want to run a query without this scope, you can
        // use: Project::withoutGlobalScope(AccountScope::class)->get();
        static::addGlobalScope(new AccountScope);
    }
}
