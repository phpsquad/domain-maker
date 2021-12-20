<?php

namespace PhpSquad\DomainMaker\Console;

use Illuminate\Console\GeneratorCommand as command;
use Illuminate\Console\Concerns\CreatesMatchingTest;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class DomainRouteMakeCommand extends command
{
    use CreatesMatchingTest;

    protected $name = 'domain:make:routes';
    protected $description = 'Create a new routes for domain';
    protected $type = 'Route';

    protected function getStub(): string
    {
        $stub = "/stubs/routes.stub";

        if ($this->option('controller')){
            $stub = "/stubs/routes-with-controller.stub";
        }

        return $this->resolveStubPath($stub);
    }

    protected function resolveStubPath($stub): string
    {
        $localPath = __DIR__ . '/src' . $stub;
        $publishedPath = $this->laravel->basePath(trim($stub, '/'));
        return file_exists($publishedPath)
            ? $publishedPath
            : $localPath;
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        $domain = $this->argument('domain');

        return $rootNamespace . '\Domains\\' . $domain . '\routes';
    }

    protected function buildClass($name)
    {
        $domain = $this->argument('domain');
        $prefix = Str::lower($domain);
        $domain = Str::studly($domain);
        $controller = $domain . 'Controller';

        $name = $this->argument('name');
        $wantsController = $this->option('controller');

        if (!empty($name)) {
            $prefix = Str::lower($name);
            $domain = Str::studly($domain);
            $controller = $name . 'Controller';
        }

        if ($wantsController){
            $controller = Str::studly($wantsController);
        }

        $replace = [
            '{{DummyPrefix}}' => $prefix,
            '{{DummyDomain}}' => $domain,
            '{{DummyController}}' => $controller,
        ];

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    protected function getArguments(): array
    {
        return [
            ['domain', InputArgument::REQUIRED, 'The domain of the class'],
            ['name', InputArgument::REQUIRED, 'The name of the class'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['controller', 'c', InputOption::VALUE_OPTIONAL, 'Generate a nested resource controller class.'],
        ];
    }
}
