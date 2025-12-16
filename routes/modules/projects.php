    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\rentman\InvoiceController;
    use App\Http\Controllers\Rentman\ProjectController;

    Route::middleware(['can:access-projects'])->group(function()
    {
      // Specific, non-resource routes
      Route::prefix('projects')->group(function () {
        Route::controller(ProjectController::class)
          ->name('projects.')
          ->group(function () {
            Route::get('/fetch', 'fetch')->name('fetch');
            Route::post('/fetch', 'fetch')->name('fetch');
          });
      });

      // Project resourse routes
      Route::resource('projects', ProjectController::class);
    });

