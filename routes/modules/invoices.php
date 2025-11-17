    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\rentman\InvoiceController;

    // Specific, non-resource routes
    Route::prefix('invoices')->group(function () {
      Route::controller(InvoiceController::class)
        ->name('invoices.')
        ->group(function () {
          Route::get('/fetch', 'fetch')->name('fetch');
        });
    });

    // Invoice resourse routes
    Route::resource('invoices', InvoiceController::class);
