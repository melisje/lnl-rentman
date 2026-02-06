<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Model;

class EndpointField extends Model
{
    protected $table = 'rm_endpoint_fields';
    // protected $primaryKey = ['endpoint','name'];
    // public $incrementing = false;
    protected $guarded = [];
    public $timestamps = false;
}
