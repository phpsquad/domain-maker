<?php

namespace PhpSquad\DomainMaker\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

/// Modified version of:
/// https://github.com/laravel/framework/blob/8.x/src/Illuminate/Foundation/Console/ResourceMakeCommand.php
class DomainServiceMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'domain:make:service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new service for a domain';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Service';


    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/service.stub');
    }


    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        $localPath = __DIR__ . '/..' . $stub;
        $publishedPath = $this->laravel->basePath(trim($stub, '/'));
        return file_exists($publishedPath)
            ? $publishedPath
            : $localPath;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $this->domain = Str::studly($this->argument('domain'));
        return $rootNamespace . '\Domains\\' . $this->domain . '\Services';
    }

    protected function getArguments()
    {
        return [
            ['domain', InputArgument::REQUIRED, 'The domain of the class'],
            ['name', InputArgument::REQUIRED, 'The name of the class'],
        ];
    }
}
