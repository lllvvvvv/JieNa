<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Collection::macro('paginate', function(int $perPage = 15, $page = null, $options = []) {
            /** @var Collection $this */
            $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
            return new LengthAwarePaginator(
                $this->forPage($page, $perPage)->values(),
                $this->count(),
                $perPage,
                $page,
                $options
            );
        });
    }


}
