<?php

namespace PhpSquad\DomainMaker\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class DomainMakeCommand extends Command
{
    protected $name = 'domain:make:domain';
    protected $description = 'Command description';
    protected $type = 'Route';
    protected function getStub()
    {
    }
    public function handle(): int
    {
        try {
            $domain = $this->argument('domain');

            $domainsDir = $this->getDomainsDir();

            $this->makeNewDomainDir($domain, $domainsDir);

            $this->info($domainsDir);
            return 0;
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return 1;
        }
    }

    private function getAppDir(): string
    {
        for ($i = 1; $i <= 10; $i++) {
            $appDir = dirname(__DIR__, $i) . '/app';

            $appDirExists = file_exists($appDir);

            if ($appDirExists) {
                break;
            }
        }

        if (!$appDirExists) {
            throw new \Exception('Could not find app directory! Command must be with in subdirectory of app');
        }

        return $appDir;
    }

    private function getDomainsDir(): string
    {
        $appDir = $this->getAppDir();

        $domainDir = $appDir . '/Domains';
        $domainDirExists = file_exists($domainDir);

        if (!$domainDirExists) {
            mkdir($domainDir);
        }

        return $domainDir;
    }

    private function makeNewDomainDir(string $domain, string $domainsDir)
    {
        $newDomainDir = $domainsDir . '/' . $domain;
        $alreadyExists = file_exists($newDomainDir);

        if ($alreadyExists) {
            throw new \Exception($domain . ' domain already exists! Will not overwrite existing domain.');
        }

        mkdir($newDomainDir);

        $folders = [
            'Http/Controllers',
            'Http/Middleware',
            'Http/Requests',
            'Repositories',
            'Models',
            'routes',
            'Exceptions',
            'Jobs',
            'Services',
            'resources/css',
            'resources/js',
            'resources/views'
        ];

        foreach ($folders as $folder) {
            $newFolder = $newDomainDir . '/' . $folder;
            mkdir($newFolder, 0777, true);
        }

        $this->createController();

        return true;
    }

    protected function createController()
    {
        $domain = Str::studly(class_basename($this->argument('domain')));
        $wantsController = $this->option('controller');

        $routesOptions = $this->getRoutesOptions($domain, $wantsController);

        if ($wantsController) {
            $controllerOptions = $this->getControllerOptions($domain, $wantsController);
            $this->call('domain:make:controller', $controllerOptions);
        }

        $this->call('domain:make:routes', $routesOptions);
    }

    protected function getControllerOptions(string $domain): array
    {
        return ['domain' => $domain, 'name' => "{$domain}Controller"];
    }

    protected function getRoutesOptions(string $domain, $wantsController): array
    {
        if ($wantsController) {

            return ['domain' => $domain, 'name' => $domain, "--controller" => "{$domain}Controller"];
        }

        return ['domain' => $domain, 'name' => $domain];
    }

    protected function getOptions()
    {
        return [
            ['controller', 'c', InputOption::VALUE_OPTIONAL, 'Generate a nested resource controller class.'],
        ];
    }

    protected function getArguments()
    {
        return [
            ['domain', InputArgument::REQUIRED, 'name of domain.'],
        ];
    }
}
