<?php

namespace Lumos\Lustom\Commands;

use Illuminate\Console\Command;

class CreateResource extends Command {
    protected $signature = 'resource:create {name} {module}';
    protected $description = 'Create lustom resource';

    public function handle() {
        $name = $this->argument('name');
        $module = $this->argument('module');
        $root = config('lustom.root');
        $namespace = config('lustom.namespace');

        if (!file_exists("$root/$module")) {
            $this->error("$root/$module does not exists");

            return;
        }

        $stub = file_get_contents(__DIR__ . '/../Stubs/Resource.stub');

        $stub = str_replace('$namespace$', $namespace, $stub);
        $stub = str_replace('$module$', $module, $stub);
        $stub = str_replace('$name$', $name, $stub);

        file_put_contents("$root/$module/Http/Resource/${name}.php", $stub);

        $this->info("Resource $namespace$module\\Http\\Resource\\$name created.");
    }
}
