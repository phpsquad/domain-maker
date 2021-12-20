<?php

namespace PhpSquad\DomainMaker\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class DomainMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:make:domain {domain} {name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


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

            if ($appDirExists){
                break;
            }
        }

        if (!$appDirExists){
            throw new \Exception('Could not find app directory! Command must be with in subdirectory of app');
        }

        return $appDir;
    }

    private function getDomainsDir(): string
    {
        $appDir = $this->getAppDir();

        $domainDir = $appDir . '/Domains';
        $domainDirExists = file_exists($domainDir);

        if (!$domainDirExists){
            mkdir($domainDir);
        }

        return $domainDir;
    }

    private function makeNewDomainDir(string $domain, string $domainsDir)
    {
        $newDomainDir = $domainsDir . '/' . $domain;
        $alreadyExists = file_exists($newDomainDir);

        if ($alreadyExists){
            throw new \Exception($domain . ' domain already exists! Will not overwrite existing domain.');
        }

        mkdir($newDomainDir);

        $folders = [
            'Repositories',
            'Models',
            'routes',
            'Http/Controllers',
            'Http/Middleware',
            'Http/Requests'
        ];

        foreach($folders as $folder){
            $newFolder = $newDomainDir . '/' . $folder;
            mkdir($newFolder, 0777, true);
        }

        $this->createController();

        return true;
    }

    protected function createController()
    {
        $domain = Str::studly(class_basename($this->argument('domain')));
        $name = !empty($this->argument('name')) ? Str::studly(class_basename($this->argument('name'))) : $domain;

        $this->call('domain:make:controller', ['domain' => $domain, 'name' => "{$domain}Controller"]);
        $this->call('domain:make:routes', ['domain' => $domain, 'name' => $name]);
    }
}
