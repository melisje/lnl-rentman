<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Rentman\Account;

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
}
