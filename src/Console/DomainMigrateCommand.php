<?php

namespace PhpSquad\DomainMaker\Console;

use Illuminate\Database\Console\Migrations\MigrateCommand;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Migrations\Migrator;
use PhpSquad\DomainMaker\helpers\DomainPathsProvider;

class DomainMigrateCommand extends MigrateCommand
{
    protected $signature = 'domain:migrate {--database= : The database connection to use}
                {--force : Force the operation to run when in production}
                {--path=* : The path(s) to the migrations files to be executed}
                {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}
                {--schema-path= : The path to a schema dump file}
                {--pretend : Dump the SQL queries that would be run}
                {--seed : Indicates if the seed task should be re-run}
                {--step : Force the migrations to be run so they can be rolled back individually}';

    public function __construct(Migrator $migrator, Dispatcher $dispatcher)
    {
        parent::__construct($migrator, $dispatcher);
    }

    protected function getMigrationPaths(): array
    {
        // Here, we will check to see if a path option has been defined. If it has we will
        // use the path relative to the root of the installation folder so our database
        // migrations may be run for any customized path from within the application.
        if ($this->input->hasOption('path') && $this->option('path')) {
            return collect($this->option('path'))->map(function ($path) {
                return !$this->usingRealPath()
                    ? $this->laravel->basePath() . '/' . $path
                    : $path;
            })->all();
        }

        return array_merge(
            $this->migrator->paths(), [$this->getMigrationPath()], $this->getDomainMigrationPaths()
        );
    }

    private function getDomainMigrationPaths(): array
    {
        $domainPaths = DomainPathsProvider::getDomainPaths();

        foreach ($domainPaths as $domainPath) {
          $this->info($domainPath);
        }
        //todo: add migrations paths
        return [];
    }
}
