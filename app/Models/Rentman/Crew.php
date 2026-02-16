<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Model;

class Crew extends Model
{
    protected $table = 'rm_crew';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $guarded = [];
    // protected $fillable = ['rm_id', 'account', 'displayname'];
}
