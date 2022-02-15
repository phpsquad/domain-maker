<?php

namespace PhpSquad\DomainMaker;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use PhpSquad\DomainMaker\Console\DomainControllerMakeCommand;
use PhpSquad\DomainMaker\Console\DomainFactoryMakeCommand;
use PhpSquad\DomainMaker\Console\DomainMakeCommand;
use PhpSquad\DomainMaker\Console\DomainMigrateCommand;
use PhpSquad\DomainMaker\Console\DomainModelMakeCommand;
use PhpSquad\DomainMaker\Console\DomainRequestMakeCommand;
use PhpSquad\DomainMaker\Console\DomainRouteMakeCommand;


class DomainMakerServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton(DomainMigrateCommand::class, function ($app) {
            return new DomainMigrateCommand($app['migrator'], new \Illuminate\Events\Dispatcher($app));
        });
    }

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
                DomainMigrateCommand::class
            ]);
        }

        $this->publishes([
            __DIR__.'/stubs/routes.stub' => base_path('stubs/routes.stub')
        ], 'domain-stubs');
    }
}
