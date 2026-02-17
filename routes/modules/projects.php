    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Rentman\llstageservice\ProjectController;

    Route::middleware(['can:access-projects'])->group(function()
    {
        Route::get('/{account}/projects', [ProjectController::class, 'index'])->name('projects.index');
    });

