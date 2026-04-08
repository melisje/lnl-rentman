    <?php

use App\Http\Controllers\testtom\ProjectFunctionsTestController;
use Illuminate\Support\Facades\Route;
    // use App\Http\Controllers\Rentman\llstageservice\ProjectController;
    // use App\Http\Controllers\testtom\ProjectFunctionsTestController;


    Route::middleware(['can:access-testtom'])
    ->prefix('testtom/project/{project}')
    ->name('testtom.')
    ->group(function () {

      Route::resource('projectfunctions', ProjectFunctionsTestController::class);

    });
