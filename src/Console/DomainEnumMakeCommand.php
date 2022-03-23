<?php

namespace PhpSquad\DomainMaker\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;


class DomainEnumMakeCommand extends GeneratorCommand
{
    protected $name = 'domain:make:enum';
    protected $description = 'Creates a new Enum for a domain';
    protected $type = 'Enum';

    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/enum.stub');
    }

    protected function resolveStubPath($stub)
    {
        $localPath = __DIR__ . '/..' . $stub;
        $publishedPath = $this->laravel->basePath(trim($stub, '/'));
        return file_exists($publishedPath)
            ? $publishedPath
            : $localPath;
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        $this->domain = Str::studly($this->argument('domain'));
        return $rootNamespace . '\Domains\\' . $this->domain . '\Enums';
    }

    protected function getArguments()
    {
        return [
            ['domain', InputArgument::REQUIRED, 'The domain of the class'],
            ['name', InputArgument::REQUIRED, 'The name of the class'],
        ];
    }
}
