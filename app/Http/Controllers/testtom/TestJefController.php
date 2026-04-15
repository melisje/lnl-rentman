<?php

namespace App\Http\Controllers\testtom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestJefController extends Controller
{
    //
    public function projects_with_account_scope()
    {
        $projects = \App\Models\Rentman\Project::all();
        return $projects;
    }

}
