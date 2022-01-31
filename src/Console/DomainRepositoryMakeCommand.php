<?php

namespace PhpSquad\DomainMaker\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/// Modified version of:
/// https://github.com/laravel/framework/blob/8.x/src/Illuminate/Foundation/Console/ResourceMakeCommand.php
class DomainRepositoryMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'domain:make:repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new repository for a domain';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';
    protected $domain;
    protected $model;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = $this->getSourceFilePath();

        $this->makeDirectory(dirname($path));

        $contents = $this->getSourceFile();

        if (!$this->files->exists($path)) {
            $this->files->put($path, $contents);
            $this->info("File : {$path} created");
        } else {
            $this->info("File : {$path} already exits");
        }
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/repository.stub');
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
     * @return string
     */
    protected function getTheNamespace()
    {
        $rootNamespace = $this->laravel->getNamespace();
        $this->domain = Str::studly($this->argument('domain'));
        return $rootNamespace . 'Domains\\' . $this->argument("domain") . '\Repositories';
    }

    protected function getArguments()
    {
        return [
            ['domain', InputArgument::REQUIRED, 'The domain of the class'],
            ['name', InputArgument::REQUIRED, 'The name of the class'],
            ['model', InputArgument::REQUIRED, 'The model to generate a  repository for'],
        ];
    }

    /**
     * Get the full path of generate class
     *
     * @return string
     */
    public function getSourceFilePath()
    {
        $filename = $this->argument("name");
        $domain = $this->argument("domain");
        return base_path("App\\Domains\\$domain\\Repositories\\$filename.php");
    }


    /**
     * Get the stub path and the stub variables
     *
     * @return bool|mixed|string
     *
     */
    public function getSourceFile()
    {

        return $this->getStubContents($this->getStub(), $this->getStubVariables());
    }

    /**
     * Replace the stub variables(key) with the desire value
     *
     * @param $stub
     * @param array $stubVariables
     * @return bool|mixed|string
     */
    public function getStubContents($stub, $stubVariables = [])
    {
        $contents = file_get_contents($stub);

        foreach ($stubVariables as $search => $replace) {
            $contents = str_replace($search, $replace, $contents);
        }

        return $contents;
    }

    /**
     **
     * Map the stub variables present in stub to its value
     *
     * @return array
     *
     */
    public function getStubVariables()
    {
        return [
            '{{ namespace }}' => $this->getTheNamespace(),
            '{{ class }}'     => $this->argument('name'),
            '{{ domain }}'    => $this->argument('domain'),
            '{{ model }}'     => $this->argument('model')
        ];
    }
}
