<?php

namespace Lumos\Lustom;

use Illuminate\Support\ServiceProvider;
use Lumos\Lustom\Commands\CreateEndpoint;
use Lumos\Lustom\Commands\CreateMiddleware;
use Lumos\Lustom\Commands\CreateModule;
use Lumos\Lustom\Commands\CreateResource;
use Lumos\Lustom\Commands\CreateValidator;

class LustomServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
        $this->mergeConfigFrom(__DIR__ . '/config.php', 'lustom');

        $this->commands([
            CreateModule::class,
            CreateEndpoint::class,
            CreateValidator::class,
            CreateMiddleware::class,
            CreateResource::class
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() {
        $this->publishes([
            __DIR__ . '/config.php' => config_path('lustom.php')
        ]);
    }
}
