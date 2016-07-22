<?php

namespace Sands\Asasi\Foundation;

use Sands\Asasi\Booted\BootedTrait;
use Sands\Asasi\Foundation\Policy\Policy;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class FoundationServiceProvider extends ServiceProvider
{
    use BootedTrait;

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {   
        $this->registerRoutes();
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootBootedTrait();
        $this->bootValidators();
        $this->bootPolicy();
    }

    /**
     * After application bootstrapped.
     *
     * @return void
     */
    public function booted()
    {
        $this->bootedLanguage();
    }

    protected function registerRoutes()
    {
        $this->app->router->group([
            'middleware' => 'web'
        ], function ($router) {
            $router->Get('set/{locale}', ['as' => 'locale', function($locale) {
                return redirect()->back()->withCookie(cookie()->forever('locale', $locale));
            }]);
        });
    }

    public function bootPolicy()
    {
        $this->app->singleton('policy', function() {
            return new Policy($this->app->auth->guard());
        });
    }

    protected function bootValidators()
    {
        $this->app->validator->extend('matchesHashedPassword', function($attribute, $value, $parameters)
        {
            return $this->app->hash->check($value, $parameters[0]);
        });
    }

    protected function bootedLanguage()
    {
        $this->app->setLocale(request()->cookie('locale') ?: 'en');
    }
}
