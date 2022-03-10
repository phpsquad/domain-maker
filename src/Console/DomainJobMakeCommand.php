<?php

namespace PhpSquad\DomainMaker\Console;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Str;

/// Modified version of:
/// https://github.com/laravel/framework/blob/8.x/src/Illuminate/Foundation/Console/JobMakeCommand.php
class DomainJobMakeCommand extends GeneratorCommand
{
    use CreatesMatchingTest;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'domain:make:job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new job class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Job';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->option('sync')
            ? $this->resolveStubPath('/stubs/job.stub')
            : $this->resolveStubPath('/stubs/job.queued.stub');
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
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $this->domain = Str::studly($this->argument('domain'));
        return $rootNamespace . '\Domains\\' . $this->domain . '\\Jobs';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['sync', null, InputOption::VALUE_NONE, 'Indicates that job should be synchronous'],
        ];
    }

    protected function getArguments()
    {
        return [
            ['domain', InputArgument::REQUIRED, 'The domain of the class'],
            ['name', InputArgument::REQUIRED, 'The name of the class'],
        ];
    }
}
