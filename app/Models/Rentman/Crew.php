<?php

namespace App\Models\Rentman;

use App\Scopes\AccountScope;
use Illuminate\Database\Eloquent\Model;

class Crew extends Model
{
    protected $table = 'rm_crew';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $guarded = [];
    // protected $fillable = ['rm_id', 'account', 'displayname'];

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
