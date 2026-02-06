<?php

namespace App\Http\Controllers\Rentman;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EndpointFieldController extends Controller
{
    protected $table = 'rm_endpoint_fields';
    protected $primaryKey = 'id';
    public $incrementing = false;
    //
}
