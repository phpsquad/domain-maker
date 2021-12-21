<?php

namespace PhpSquad\DomainMaker\Console;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class DomainGeneratorCommand extends GeneratorCommand
{

    protected $domain;

    protected function getStub()
    {
        // TODO: Implement getStub() method.
    }

    protected function getArguments()
    {
        return [
            ['domain', InputArgument::REQUIRED, 'The domain of the class'],
            ['name', InputArgument::REQUIRED, 'The name of the class'],
        ];
    }
}
