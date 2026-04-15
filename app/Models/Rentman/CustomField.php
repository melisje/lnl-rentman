<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Rentman\Account;
use App\Scopes\AccountScope;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomField extends Model
{
    protected $table = 'rm_customfields';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $guarded = [];


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


    /**
     * Relatie naar het Rentman Account model.
     */
    public function rentmanAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account', 'account');
    }

    /**
     * Relatie naar de CustomFieldMapping model.
     */
    public function mappings(): HasMany
    {
        return $this->hasMany(CustomFieldMapping::class, 'customfield_id', 'id');
    }
}
