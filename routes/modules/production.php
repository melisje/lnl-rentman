<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Production\ProjectTimeRegistrationController;
use App\Http\Controllers\Rentman\llstageservice\ProjectController;

/*
* Here is where you can register web routes for your application. These
* routes are loaded by the RouteServiceProvider within a group which
* contains the "web" middleware group.
*/
Route::middleware(['can:access-production'])
  ->prefix('production/project')
  ->name('production.project.')
  ->group(function ()
    {
      Route::resource('timeregistration', ProjectTimeRegistrationController::class);
    });

