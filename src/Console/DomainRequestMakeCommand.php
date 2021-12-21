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

    protected function resolveStubPath($stub): string
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
                        ? $customPath
                        : __DIR__.$stub;
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        $this->domain = Str::studly($this->argument('domain'));
        return $rootNamespace . '\Domains\\' . $this->domain.'\Http\Requests';
    }
}
