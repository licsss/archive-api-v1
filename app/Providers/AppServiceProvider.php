<?php

namespace App\Providers;

use App\Repository\AuthenticatedUser;
use App\Repository\VirtualAccountAuthenticatedUser;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public array $bindings=[
        AuthenticatedUser::class=>VirtualAccountAuthenticatedUser::class
    ];
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
