    <?php

use App\Http\Controllers\testtom\ProjectFunctionsTestController;
use Illuminate\Support\Facades\Route;
    // use App\Http\Controllers\Rentman\llstageservice\ProjectController;


    Route::middleware(['can:access-testtom'])
    ->prefix('testtom')
    ->name('testtom.')
    ->group(function () {

      Route::get('/', [ProjectFunctionsTestController::class, 'overview'])->name('index');

      Route::prefix('project/{project}')->group(function () {
        Route::resource('projectfunctions', ProjectFunctionsTestController::class);
      });

    });
