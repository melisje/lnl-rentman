<?php

use App\Http\Controllers\testtom\ProjectFunctionsTestController;
use App\Http\Controllers\testtom\TestJefController;
use Illuminate\Support\Facades\Route;

// Inertia pagina's — root niveau, geen /testtom prefix
Route::middleware(['can:access-testtom'])
    ->name('testtom.')
    ->group(function () {

        Route::get('/overview', [ProjectFunctionsTestController::class, 'overview'])->name('index');
        Route::get('/search', [ProjectFunctionsTestController::class, 'search'])->name('search');

        Route::prefix('project/{project}')->group(function () {
            Route::resource('projectfunctions', ProjectFunctionsTestController::class);
        });

        Route::get('/weekoverzicht', [TestJefController::class, 'weekoverzicht'])->name('weekoverzicht');
        Route::get('/checklist', [TestJefController::class, 'checklist'])->name('checklist');
    });

// Blade test-routes — onder /develop
Route::middleware(['can:access-testtom'])
    ->prefix('develop')
    ->name('develop.')
    ->group(function () {
        Route::get('testjef1', [TestJefController::class, 'test1'])->name('testjef1');
        Route::get('testjef2', [TestJefController::class, 'test2'])->name('testjef2');
        Route::get('testjef3', [TestJefController::class, 'test3'])->name('testjef3');
    });
