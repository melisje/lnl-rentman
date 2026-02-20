    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Rentman\llstageservice\ProjectController;


    Route::middleware(['can:access-projects'])->group(function()
    {
        Route::get('/projects', [ProjectController::class, 'index'])
            ->name('rentman.projects.index');

            Route::get('/projects/{project}', [ProjectController::class, 'show'])
            ->name('rentman.projects.show');
    });

