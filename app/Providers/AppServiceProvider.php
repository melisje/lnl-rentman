<?php

namespace App\Providers;

use App\Models\Rentman\Account;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Bind de data aan je navigatie-view (pas het pad aan naar jouw navbar blade file)
        View::composer(['layouts.app', 'layouts.inertia'], function ($view) {
            $current_account = session('current_account', null);
            $view->with('current_account', $current_account)
                 ->with('globalAccounts', Account::all())
                 ->with('currentLocale', app()->getLocale())
                 ->with('supportedLocales', ['en' => 'English', 'nl' => 'Nederlands']);
        });

        // Definieer de macro
        Blueprint::macro('dbTimestamps', function () {
            $this->timestamp('created_at')->useCurrent();
            $this->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        // Admin rights
        Gate::define('access-admin',function(User $user){
            return $user->hasRole('admin');
        });

        // Role rights
        Gate::define('access-roles', function (User $user) {
            return $user->hasAnyRole(['roles', 'admin']);
        });

        // Project rights
        Gate::define('access-projects', function (User $user) {
            return $user->hasAnyRole(['projects', 'admin']);
        });

        // invoices rights
        Gate::define('access-invoices', function (User $user) {

            return $user->hasAnyRole(['invoices','admin']);
        });

        // api tokens rights
        Gate::define('access-apitokens', function (User $user) {

            return $user->hasAnyRole(['api_tokens','admin']);
        });
        // webhookcalls rights
        Gate::define('access-webhookcalls', function (User $user) {

            return $user->hasAnyRole(['webhookcalls','admin']);
        });

        // accounts rights
        Gate::define('access-accounts', function (User $user) {

            return $user->hasAnyRole(['accounts','admin']);
        });

        // accounts rights
        Gate::define('access-customfields', function (User $user) {

            return $user->hasAnyRole(['customfields','admin']);
        });
        // purchase rights
        Gate::define('access-purchase', function (User $user) {

            return $user->hasAnyRole(['purchase','admin']);
        });

        // checklist rights
        Gate::define('access-checklists', function (User $user) {

            return $user->hasAnyRole(['checklists','admin']);
        });
        // testtom rights
        Gate::define('access-testtom', function (User $user) {

            return $user->hasAnyRole(['testtom','admin']);
        });
    }
}
