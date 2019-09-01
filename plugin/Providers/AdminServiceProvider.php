<?php
namespace Plugin\Providers;

use TinyPixel\Base\ServiceProvider as BaseProvider;

class AdminServiceProvider extends BaseProvider
{
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
        $this->loadViewsFrom(__DIR__ . '/../../resources/admin/views', 'admin');
    }
}
