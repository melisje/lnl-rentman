    <?php

use App\Http\Controllers\Rentman\WebhookCallController;
use Illuminate\Support\Facades\Route;

    Route::middleware(['can:access-webhookcalls'])
      ->group(function ()
      {
        // Project resourse routes
        Route::resource('webhookcall', WebhookCallController::class);
      });
