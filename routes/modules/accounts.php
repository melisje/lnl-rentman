    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Rentman\llstageservice\ProjectController;


    Route::get('switch-account/{account}', function ($account)
    {
        auth()->user()->update(['current_account' => $account]);
        session(['current_account' => $account]);

        return redirect()->back();
    })->name('account.switch');