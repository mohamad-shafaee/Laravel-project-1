<?php
namespace Summit\Admin\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Summit\Admin\View\Components\AppLayout;
use Summit\Admin\View\Components\GuestLayout;
use Summit\Admin\View\Components\AuthCard;
use Summit\Admin\View\Components\ApplicationLogo;
use Summit\Admin\View\Components\AuthSessionStatus;
use Summit\Admin\View\Components\AuthValidationErrors;
use Summit\Admin\View\Components\Button;
use Summit\Admin\View\Components\Label;
use Summit\Admin\View\Components\Input;

/**
* AdminServiceProvider
*
* @copyright 2020 Webkul Software Pvt. Ltd.
*/
class AdminServiceProvider extends ServiceProvider
{
    /**
    * Bootstrap services.
    *
    * @return void
    */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Http/admin-routes.php');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'admin'); 
        // The second argumet, 'admin', is the namespace
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'admin');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        // The tag for component <x-app-layout> ...
        Blade::component('admin-app-layout', AppLayout::class);
        Blade::component('admin-guest-layout', GuestLayout::class);
        Blade::component('admin-auth-card', AuthCard::class);
        Blade::component('admin-application-logo', ApplicationLogo::class);
        Blade::component('admin-auth-session-status', AuthSessionStatus::class);
        Blade::component('admin-auth-validation-errors', AuthValidationErrors::class);
        Blade::component('admin-button', Button::class);
        Blade::component('admin-label', Label::class);
        Blade::component('admin-input', Input::class);

        $this->publishes([
        __DIR__.'/../Public/css' => public_path('css/admin'),
    ], 'public');
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
