<?php

namespace PhpSquad\DomainMaker\Console;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class DomainRepositoryMakeCommand extends GeneratorCommand
{
    protected $name = 'domain:make:repository';
    protected $description = 'Creates a new repository for a domain';

    protected $type = 'Repository';
    protected string $domain;
    protected string $model;

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

    protected function getStub(): string
    {
        return $this->resolveStubPath('/stubs/repository.stub');
    }

    protected function resolveStubPath($stub): string
    {
        $localPath = __DIR__ . '/..' . $stub;
        $publishedPath = $this->laravel->basePath(trim($stub, '/'));
        return file_exists($publishedPath)
            ? $publishedPath
            : $localPath;
    }

    protected function getTheNamespace(): string
    {
        $rootNamespace = $this->laravel->getNamespace();
        $this->domain = Str::studly($this->argument('domain'));
        return $rootNamespace . 'Domains\\' . $this->argument("domain") . '\Repositories';
    }

    protected function getArguments(): array
    {
        return [
            ['domain', InputArgument::REQUIRED, 'The domain of the class'],
            ['name', InputArgument::REQUIRED, 'The name of the class'],
            ['model', InputArgument::REQUIRED, 'The model to generate a  repository for'],
        ];
    }

    public function getSourceFilePath(): string
    {
        $filename = $this->argument("name");
        $domain = $this->argument("domain");

        $path =  base_path("app/Domains/$domain/Repositories/$filename.php");

        return $path;
    }

    public function getSourceFile(): bool|array|string
    {
        return $this->getStubContents($this->getStub(), $this->getStubVariables());
    }

    public function getStubContents($stub, $stubVariables = []): array|bool|string
    {
        $contents = file_get_contents($stub);

        foreach ($stubVariables as $search => $replace) {
            $contents = str_replace($search, $replace, $contents);
        }

        return $contents;
    }

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
