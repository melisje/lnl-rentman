<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'rm_contacts';
    protected $primaryKey = 'id';
    public $incrementing = false;
}
