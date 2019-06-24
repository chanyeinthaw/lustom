<?php

namespace Lumos\Lustom;

use Illuminate\Support\ServiceProvider;
use Lumos\Lustom\Commands\CreateModule;

class LustomServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
        $this->mergeConfigFrom(__DIR__ . '/config.php', 'lustom');

        $this->commands([CreateModule::class]);
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
