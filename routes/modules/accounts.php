    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Rentman\llstageservice\ProjectController;


    Route::get('switch-account/{account}', function ($account)
    {
        session(['current_account' => $account]);
        return redirect()->back();
    })->name('account.switch');