<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\LDAP\LDAPRepository;
use App\Repositories\Contracts\LDAP\LDAPRepositoryInterface;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LDAPRepositoryInterface::class, LDAPRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
