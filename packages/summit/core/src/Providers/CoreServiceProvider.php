<?php
namespace Summit\Core\Providers;

use Illuminate\Support\ServiceProvider;

/**
* CoreServiceProvider
*
* @copyright 2020 Webkul Software Pvt. Ltd.
*/
class CoreServiceProvider extends ServiceProvider
{
    /**
    * Bootstrap services.
    *
    * @return void
    */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Http/core-routes.php');
        //$this->loadViewsFrom(__DIR__ . '/../Resources/views', 'core'); 
        // The second argumet, 'core', is the namespace
        //$this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'core');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

    }

    /**
    * Register services.
    *
    * @return void
    */
    public function register()
    {

    }
}
