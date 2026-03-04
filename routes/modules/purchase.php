    <?php

use App\Http\Controllers\Purchase\InvoiceController;
use App\Http\Controllers\Purchase\SupplierController;
use Illuminate\Support\Facades\Route;



    Route::middleware('can:access-purchase')
      ->prefix('purchase')
      ->name('purchase.')
      ->group(function()
      {
        //   Route::resource('invoices', InvoiceController::class)
              // ->only(['index', 'show'])
            //   ;


        Route::resource('suppliers', SupplierController::class);


        // De JSON data bron voor Tabulator
        Route::get('/supplier/data', [SupplierController::class, 'getSupplierData'])->name('api.supplier.data');

        // Optioneel: Route voor inline editing (updates)
        Route::post('/supplier/update/{id}', [SupplierController::class, 'updateSupplier'])->name('api.supplier.update');

      });

