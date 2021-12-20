<?php

namespace PhpSquad\DomainMaker\Console;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Illuminate\Console\GeneratorCommand as command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class DomainRouteMakeCommand extends command
{
    use CreatesMatchingTest;

    protected $name = 'domain:make:routes';
    protected $description = 'Create a new routes for domain';
    protected $type = 'Route';

    protected function getStub(): string
    {
        $stub = "/stubs/routes.stub";

        return $this->resolveStubPath($stub);
    }

    protected function resolveStubPath($stub): string
    {
        $localPath = __DIR__ . '/..' . $stub;
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

        if (!empty($name)) {
            $prefix = Str::lower($name);
            $domain = Str::studly($domain);
            $controller = $name . 'Controller';
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
            ['name', InputArgument::OPTIONAL, 'The name of the class'],
        ];
    }
}
