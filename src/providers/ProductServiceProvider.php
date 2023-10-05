<?php

namespace Product\Category;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
            $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
            ], 'product-category');
            $this->publishes([
              __DIR__.'/../Controllers/' => app_path('Http/Controllers')
            ], 'lead');
            $this->publishes([
              __DIR__.'/../Models/' => app_path('Models')
            ], 'lead');
            $this->publishes([
              __DIR__.'/../Requests' => app_path('Http/Requests')
            ], 'lead');
    }
}



