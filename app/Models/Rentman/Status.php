<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'rm_statuses';

    protected $guarded =
    [
        'id'
    ];
}
