<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    protected $table = 'rm_customfields';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $guarded = [];

}
