<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\Rentman\AccountController;
use App\Http\Controllers\Rentman\CustomFieldController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'can:access-admin'])->prefix('admin')->name('admin.')->group(function () {
  // Admin Dashboard
  Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

  // Admin > Roles
  Route::middleware(['can:access-roles'])->prefix('roles')->name('roles.')->group(function () {
    Route::get('/', [UserRoleController::class, 'index'])->name('index');
    Route::patch('/{role}/user/{user}', [UserRoleController::class, 'update'])->name('user.toggle');
  });

  // Admin > Rentman (Accounts & Custom Fields)
  Route::prefix('rentman')->name('rentman.')->group(function () {
    // Accounts
    Route::middleware(['can:access-accounts'])->group(function () {
      Route::resource('accounts', AccountController::class)
        ->parameters(['accounts' => 'account']);
    });

    // Custom Fields
    Route::middleware(['can:access-customfields'])->group(function () {
      Route::resource('customfield', CustomFieldController::class)
        ->parameters([
          'customfield' => 'customField'
        ]);
    });
  });
});
