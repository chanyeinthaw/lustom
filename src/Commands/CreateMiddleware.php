<?php

namespace Lumos\Lustom\Commands;

use Illuminate\Console\Command;

class CreateMiddleware extends Command {
    protected $signature = 'middleware:create {name} {module}';
    protected $description = 'Create lustom middleware';

    public function handle() {
        $name = $this->argument('name');
        $module = $this->argument('module');
        $root = config('lustom.root');
        $namespace = config('lustom.namespace');

        if (!file_exists("$root/$module")) {
            $this->error("$root/$module does not exists");

            return;
        }

        $stub = file_get_contents(__DIR__ . '/../Stubs/Middleware.stub');

        $stub = str_replace('$namespace$', $namespace, $stub);
        $stub = str_replace('$module$', $module, $stub);
        $stub = str_replace('$name$', $name, $stub);

        file_put_contents("$root/$module/Http/Middleware/${name}.php", $stub);

        $this->info("Middleware $namespace$module\\Http\\Middleware\\$name created. Don't forget to register it in Kernel.");
    }
}
