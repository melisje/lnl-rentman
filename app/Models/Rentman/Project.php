<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'rm_projects';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $casts = [
        'planperiod_start2' => 'datetime',
    ];
    /**
     * The attributes that aren't mass assignable.
     * An empty list means that all fields are mass assignable
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>|bool
     */
    // protected $fillable = [
    //     'created',
    //     'modified',
    //     'displayname',
    //     'name',
    //     'planperiod_start',
    //     'planperiod_end',
    //     'ussageperiod_start',
    //     'ussageperiod_end',
    //     'custom_1',
    //     'custom_2',
    //     'custom_32',
    //     'custom_33',
    //     'updateHash',
    //     ];


    // const CREATED_AT = '_created';
    // const UPDATED_AT = '_updated';
}
