<?php

namespace App\Providers;

use App\Repositories\PublicityRepository;
use App\Repositories\PublicityRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(\App\Repositories\AdminRepository::class, \App\Repositories\AdminRepositoryEloquent::class);
        //:end-bindings:
        $this->app->bind(PublicityRepository::class,PublicityRepositoryEloquent::class);
    }
}
