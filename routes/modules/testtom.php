    <?php

use App\Http\Controllers\testtom\ProjectFunctionsTestController;
use App\Http\Controllers\testtom\TestJefController;
use Illuminate\Support\Facades\Route;
    // use App\Http\Controllers\Rentman\llstageservice\ProjectController;


    Route::middleware(['can:access-testtom'])
    ->prefix('testtom')
    ->name('testtom.')
    ->group(function () {

      Route::get('/', [ProjectFunctionsTestController::class, 'overview'])->name('index');
      Route::get('/search', [ProjectFunctionsTestController::class, 'search'])->name('search');

      Route::prefix('project/{project}')->group(function () {
        Route::resource('projectfunctions', ProjectFunctionsTestController::class);
      });



      /**
       * --- IGNORE ---
       * Just for testing by Jef Melis and can be removed after testing
       */
      // Test route to check if the AccountScope is working correctly on the Project model
      Route::get('testjef1', [TestJefController::class, 'test1'])->name('projects_with_account_scope');
      Route::get('testjef2', [TestJefController::class, 'test2'])->name('projecttypes');



    });


