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


      Route::get('projects_with_account_scope', [TestJefController::class, 'projects_with_account_scope'])->name('projects_with_account_scope');

    });


