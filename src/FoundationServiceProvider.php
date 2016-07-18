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
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootBootedTrait();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {   
        $this->registerRoutes();
        $this->registerValidators();
    }

    public function booted()
    {
        $this->bootedLanguage();
    }

    protected function bootedLanguage()
    {
        $this->app->setLocale(request()->cookie('locale') ?: 'en');
    }

    public function registerPolicy()
    {
        $this->app->singleton('policy', function() {
            return new Policy();
        });
    }

    protected function registerLanguage()
    {
        //
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

    protected function registerValidators()
    {
        $this->app->validator->extend('matchesHashedPassword', function($attribute, $value, $parameters)
        {
            return $this->app->hash->check($value, $parameters[0]);
        });
    }
}
