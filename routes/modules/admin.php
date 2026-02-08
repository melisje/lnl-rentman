    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Admin\AdminController;
    use App\Http\Controllers\Admin\UserRoleController;
    use App\Http\Controllers\Rentman\ApiTokenController;
use App\Models\Rentman\ApiToken;

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    | Requirements:
    | 1. Authenticated (middleware:auth)
    | 2. Authorised (middleware:can)
    */

    Route::middleware(['auth'])
      ->prefix('admin')
      ->name('admin.')
      ->group(function ()
      {

        // Route to the admin dashboard
        Route::middleware(['can:access-admin'])
          ->get('/', [AdminController::class, 'dashboard'])
          ->name('dashboard')
          ;

        /*
        |-----------------------------------------------------------------------
        | Admin > Roles Routes
        |-----------------------------------------------------------------------
        | Requirements:
        | 1. Authenticated (middleware:auth)
        | 2. Authorised (middleware:can)
        */
        Route::middleware(['auth','can:access-admin','can:access-roles'])
            ->prefix('/roles')
            ->group(function()
              {
                  Route::get('/', [UserRoleController::class, 'index'])->name('roles');
                  Route::patch('/{role}/user/{user}', [UserRoleController::class, 'update'])->name('roles.user.toggle');
              });

        /*
        |-----------------------------------------------------------------------
        | Admin > Rentman API Tokens Routes
        |-----------------------------------------------------------------------
        | Requirements:
        | 1. Authenticated (middleware:auth)
        | 2. Authorised (middleware:can)
        */
        Route::middleware(['auth','can:access-admin','can:access-apitokens'])
            // ->prefix('/apitokens')
            ->group(function()
              {
                Route::resource('apitoken',ApiTokenController::class);

              });
      });
