<?php

namespace PhpSquad\DomainMaker\Console;

use Illuminate\Support\Str;

class DomainRequestMakeCommand extends DomainGeneratorCommand
{
    protected $name = 'domain:make:request';
    protected $description = 'Create a new domain form request class';
    protected $type = 'Request';

    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/request.stub');
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

    protected function getDefaultNamespace($rootNamespace): string
    {
        $this->domain = Str::studly($this->argument('domain'));
        return $rootNamespace . '\Domains\\' . $this->domain . '\Http\Requests';
    }
}
