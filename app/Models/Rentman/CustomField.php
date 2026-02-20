<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Rentman\Account;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomField extends Model
{
    protected $table = 'rm_customfields';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $guarded = [];


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
