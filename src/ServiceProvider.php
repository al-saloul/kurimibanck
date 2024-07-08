<?php

namespace AlSaloul\KuraimibankPayment;

use AlSaloul\KuraimibankPayment\Http\Middleware\Webhook;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as SupportServiceProvider;

class ServiceProvider extends SupportServiceProvider
{
    /**
     * 
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'kuraimibank');
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/config.php' => config_path('kuraimibank.php'),
        ], 'config');

        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('webhook', Webhook::class);

        $this->registerRoutes();
    }

    
    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/webhook.php');
        });
    }

    protected function routeConfiguration()
    {
        return [
            'middleware' => 'webhook',
        ];
    }
}