<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Locale;
use App\Http\Requests\Auth\LoginByEmailRequest;
use App\Http\Requests\Auth\LoginByPhoneRequest;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Binding the Locale model
        $this->app->singleton(Locale::class, function($app){
            return new Locale();
        });

        /*$this->app->bind(LoginByPhoneRequest::class, function($app){
            return new LoginByPhoneRequest();
        }); */

        /*$this->app->bind(LoginByEmailRequest::class, function($app){
            return new LoginByEmailRequest();
        });*/
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
