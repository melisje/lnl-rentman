<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('Home');
})->name('root');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Add auth routes
Auth::routes();

// Utility routes
require __DIR__ . '/modules/accounts.php';
require __DIR__ . '/modules/language.php';

// Blade pagina's — onder /develop (eerst, zodat root route namen winnen)
Route::prefix('develop')->group(function () {
    require __DIR__ . '/modules/admin.php';
    require __DIR__ . '/modules/checklists.php';
    require __DIR__ . '/modules/invoices.php';
    require __DIR__ . '/modules/production.php';
    require __DIR__ . '/modules/projects.php';
    require __DIR__ . '/modules/purchase.php';
    require __DIR__ . '/modules/tests.php';
});

// Inertia pagina's — root niveau (als laatste, zodat route() namen naar root wijzen)
require __DIR__ . '/modules/webhooks.php';
require __DIR__ . '/modules/testtom.php';
require __DIR__ . '/modules/checklists.php';
require __DIR__ . '/modules/production.php';
