<?php
// routes/modules/language.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;

Route::get('language/{locale}', [LanguageController::class, 'switch'])
  ->name('language.switch')
  ->whereIn('locale', ['en', 'nl', 'fr']); // Make sure only accepted codes will be passed