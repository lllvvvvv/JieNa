<?php

namespace App\Providers;

use App\Repositories\HousekeepRepository;
use App\Repositories\HousekeepRepositoryEloquent;
use App\Repositories\OrderRepository;
use App\Repositories\OrderRepositoryEloquent;
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
        $this->app->bind(OrderRepository::class,OrderRepositoryEloquent::class);
        $this->app->bind(HousekeepRepository::class,HousekeepRepositoryEloquent::class);
    }
}
