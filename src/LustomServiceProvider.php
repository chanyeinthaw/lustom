<?php

namespace Lumos\Lustom;

use Illuminate\Support\ServiceProvider;

class LustomServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
        $this->mergeConfigFrom(__DIR__ . '/config.php', 'lustom');
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
