<?php

namespace Lumos\Lustom\Commands;

use Illuminate\Console\Command;

class CreateModule extends Command {
    protected $signature = 'module:create {name}';
    protected $description = 'Create lustom module';

    public function handle() {
        $name = $this->argument('name');
        $root = config('lustom.root');
        $namespace = config('lustom.namespace');

        $this->checkAndCreateRoot();
        if (!$this->createDirectory($name)) return;

        $this->createApi($name);
        $this->createStub($name, __DIR__ . '/../Stubs/Store.stub', 'Store');
        $this->createStub($name, __DIR__ . '/../Stubs/Kernel.stub', 'Kernel');
        $this->createStub($name, __DIR__ . '/../Stubs/Provider.stub', 'Provider');
        $this->createWithStoreStub($name, __DIR__ . '/../Stubs/WithUserStore.stub');

        $this->info("Module created add $namespace$name\\${name}Provider::class to providers");
    }

    private function createApi($name) {
        $root = config('lustom.root');

        $apiStub = file_get_contents(__DIR__ . '/../Stubs/api.stub');
        file_put_contents("$root/$name/api.php", $apiStub);
    }

    private function createWithStoreStub($name, $stub) {
        $root = config('lustom.root');
        $namespace = config('lustom.namespace');
        $stub = file_get_contents($stub);

        $stub = str_replace('$namespace$', $namespace, $stub);
        $stub = str_replace('$name$', $name, $stub);

        file_put_contents("$root/$name/SubStore/With${name}Store.php", $stub);
    }

    private function createStub($name, $stub, $type) {
        $root = config('lustom.root');
        $namespace = config('lustom.namespace');
        $stub = file_get_contents($stub);

        $stub = str_replace('$namespace$', $namespace, $stub);
        $stub = str_replace('$name$', $name, $stub);

        file_put_contents("$root/$name/${name}${type}.php", $stub);
    }

    private function createDirectory($name) {
        $root = config('lustom.root');
        if (file_exists("$root/$name")) {
            $this->error('Module already exists');

            return false;
        }

        mkdir("$root/$name");
        mkdir("$root/$name/File");
        mkdir("$root/$name/SubStore");
        mkdir("$root/$name/Http");
        mkdir("$root/$name/Http/Middleware");
        mkdir("$root/$name/Http/Endpoint");
        mkdir("$root/$name/Http/Resource");
        mkdir("$root/$name/Http/Validator");

        return true;
    }

    private function checkAndCreateRoot() {
        $root = config('lustom.root');
        $namespace = config('lustom.namespace');

        $composerJson = base_path('composer.json');
        $composer = json_decode(file_get_contents($composerJson), true);

        if (!isset($composer['autoload']['psr-4'][$namespace])) {
            $composer['autoload']['psr-4'][$namespace]= $root;
            $this->info('Setting Namespace');

            file_put_contents($composerJson, json_encode($composer, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
            shell_exec('composer du');
        }

        if (!file_exists(base_path($root))) {
            $this->info('Creating root directory');

            mkdir(base_path($root));
        }
    }
}
