    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\rentman\InvoiceController;

    Route::middleware(['can:access-invoices'])->group(function()
    {
      // Specific, non-resource routes
      Route::prefix('invoices')->group(function () {
        Route::controller(InvoiceController::class)
          ->name('invoices.')
          ->group(function () {
            Route::get('/fetch', 'fetchview')->name('fetchview');
            Route::post('/fetch', 'fetch')->name('fetch');
          });
      });

      // Invoice resourse routes
      Route::resource('invoices', InvoiceController::class);
    });

