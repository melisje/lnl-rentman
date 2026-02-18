<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mapping extends Model
{
    protected $table = 'rm_mappings';

    protected $fillable = [
        'id',
        'display_name',
    ];

    public $incrementing = false;

}
