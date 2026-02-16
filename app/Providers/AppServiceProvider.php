<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
    }
}
