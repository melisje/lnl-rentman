<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Path to the subfolder where all routing files will be collected
$moduleRoutesPath = __DIR__ . '/modules/*.php';

Route::get('/', function () {
    return view('welcome');
});

// Add auth routes
Auth::routes();

// Add all routes that require authentication
Route::middleware(['auth'])->group(function() use($moduleRoutesPath)
{
});

// Use the PHP 'glob'function to find all files ending on .php in the /modules folder
foreach (glob($moduleRoutesPath) as $filename) {
    // require all found files. This loads and registers the routes.
    require $filename;
}


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
