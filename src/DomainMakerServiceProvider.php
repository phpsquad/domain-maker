<?php

namespace PhpSquad\DomainMaker;

use Illuminate\Support\ServiceProvider;
use PhpSquad\DomainMaker\Console\DomainControllerMakeCommand;
use PhpSquad\DomainMaker\Console\DomainEnumMakeCommand;
use PhpSquad\DomainMaker\Console\DomainExceptionMakeCommand;
use PhpSquad\DomainMaker\Console\DomainFactoryMakeCommand;
use PhpSquad\DomainMaker\Console\DomainJobMakeCommand;
use PhpSquad\DomainMaker\Console\DomainMakeCommand;
use PhpSquad\DomainMaker\Console\DomainModelMakeCommand;
use PhpSquad\DomainMaker\Console\DomainRepositoryMakeCommand;
use PhpSquad\DomainMaker\Console\DomainRequestMakeCommand;
use PhpSquad\DomainMaker\Console\DomainResourceMakeCommand;
use PhpSquad\DomainMaker\Console\DomainRouteMakeCommand;
use PhpSquad\DomainMaker\Console\DomainServiceMakeCommand;

class DomainMakerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                DomainMakeCommand::class,
                DomainControllerMakeCommand::class,
                DomainRouteMakeCommand::class,
                DomainModelMakeCommand::class,
                DomainFactoryMakeCommand::class,
                DomainRequestMakeCommand::class,
                DomainJobMakeCommand::class,
                DomainResourceMakeCommand::class,
                DomainExceptionMakeCommand::class,
                DomainServiceMakeCommand::class,
                DomainRepositoryMakeCommand::class,
                DomainEnumMakeCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__ . '/stubs/routes.stub' => base_path('stubs/routes.stub')
        ], 'domain-stubs');
    }
}
