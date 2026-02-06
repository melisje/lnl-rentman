    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Admin\AdminController;
    use App\Http\Controllers\Admin\UserRoleController;

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
      ->group(function ()
      {

        // Route to the admin dashboard
        Route::middleware(['can:access-admin'])
          ->get('/', [AdminController::class, 'dashboard'])
          ->name('admin.dashboard');

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
                  Route::get('/', [UserRoleController::class, 'index'])->name('admin.roles');
                  Route::patch('/{role}/user/{user}', [UserRoleController::class, 'update'])->name('admin.roles.user.toggle');
              });

      });
