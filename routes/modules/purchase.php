    <?php

use App\Http\Controllers\Purchase\InvoiceController;
use Illuminate\Support\Facades\Route;



    Route::middleware('can:access-purchase')
      ->prefix('purchase')
      ->name('purchase.')
      ->group(function()
      {
          Route::resource('invoices', InvoiceController::class)
              // ->only(['index', 'show'])
              ;
      });

