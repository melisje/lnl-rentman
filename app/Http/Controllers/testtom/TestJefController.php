<?php

namespace App\Http\Controllers\testtom;

use App\Http\Controllers\Controller;
use App\Models\Rentman\Project;
use App\Scopes\AccountScope;
use Illuminate\Support\Facades\DB;

class TestJefController extends Controller
{
    //
    public function test1()
    {
        $projects = Project::orderBy('usageperiod_start')
            ->where('displayName', 'like', '%TEST%PROJECT%')
            ->where('usageperiod_start', '<', now()->addWeeks(4))
            // ->where('usageperiod_start', '>', now())
            ->get()
            // ->sortBy('weeks_until_start') // Sorteert oplopend (0, 1, 2, 3...)
            ;

        return view('testtom.project.budget.test1', [
            'models' => $projects,
        ]);


    }

}
