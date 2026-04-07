<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockLocation extends Model
{
    use HasFactory;

    protected $table = 'rm_stocklocations';

    /**
     * De velden die mass-assignable zijn.
     */
    protected $fillable = [
        'rm_id',
        'account',
        'created',
        'modified',
        'creator',
        'displayname',
        'name',
        'city',
        'street',
        'house_number',
        'postal_code',
        'state_province',
        'country',
        'active',
        'type',
        'color',
        'in_archive',
    ];

    /**
     * Casting voor types die niet standaard als string/integer behandeld moeten worden.
     */
    protected $casts = [
        'rm_id' => 'integer',
        'created' => 'datetime',
        'modified' => 'datetime',
        'active' => 'boolean',
        'in_archive' => 'boolean',
    ];
}