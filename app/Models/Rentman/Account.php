<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    // Tell Laravel the PK is a string and not incrementing
    protected $table = 'rm_accounts';
    protected $primaryKey = 'account';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'account',
        'api_token',
        'webhook_token',
        'url',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'api_token' => 'encrypted',
        'webhook_token' => 'encrypted',
    ];
}