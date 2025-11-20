<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'tbl_projects';
    protected $primaryKey = 'id';
    public $incrementing = false;

    const CREATED_AT = '_created';
    const UPDATED_AT = '_updated';
}
