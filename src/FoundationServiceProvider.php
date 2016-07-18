<?php

namespace Sands\Asasi\Foundation;

use Sands\Asasi\Booted\BootedTrait;
use Sands\Asasi\Foundation\Policy\Policy;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

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
    public function register(Router $router)
    {   
        $this->registerRoutes($router);
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
    		return new Policy()
    	});
    }

    protected function registerLanguage()
    {
    	//
    }

    protected function registerRoutes(Router $router)
    {
    	$router->group([
    		'middleware' => 'web'
    	], function ($router) {
    		$path = realpath(__DIR__.'/../');
        	require "{$path}/Http/routes.php";
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
