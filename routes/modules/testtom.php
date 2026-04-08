    <?php

use App\Http\Controllers\testtom\ProjectFunctionsTestController;
use Illuminate\Support\Facades\Route;
    // use App\Http\Controllers\Rentman\llstageservice\ProjectController;
    // use App\Http\Controllers\testtom\ProjectFunctionsTestController;


    Route::middleware(['can:access-testtom'])
    ->prefix('testtom')
    ->name('testtom.')
    ->group(function () {

      Route::get('/', function () {
        $search = request('search');
        $projects = \App\Models\Rentman\Project::orderBy('number')
          ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%")
                                      ->orWhere('number', 'like', "%{$search}%"))
          ->paginate(15)
          ->withQueryString();
        return view('testtom.index', compact('projects', 'search'));
      })->name('index');

      Route::prefix('project/{project}')->group(function () {
        Route::resource('projectfunctions', ProjectFunctionsTestController::class);
      });

    });
