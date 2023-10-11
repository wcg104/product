<?php
   
    namespace Wcg104\Product\Providers;
    use Illuminate\Support\ServiceProvider;
    
    class ProductServiceProvider extends ServiceProvider {

        public function boot()
        {
         
          $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
            ], 'product-category');
            $this->publishes([
              __DIR__.'/../Controllers/' => app_path('Http/Controllers')
            ], 'product-category');
            $this->publishes([
              __DIR__.'/../Models/' => app_path('Models')
            ], 'product-category');
            $this->publishes([
              __DIR__.'/../Requests' => app_path('Http/Requests')
            ], 'product-category');
            $this->publishes([
              __DIR__.'/../tests/Feature' => base_path('tests/Feature')
            ], 'product-category');
        }
   }
?>
