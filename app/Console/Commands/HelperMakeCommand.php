<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;


class HelperMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:helper {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Helper class';
    /**
     * Execute the console command.
     */
    protected function getStub()
    {
        return realpath(".") . '/stubs/helper.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\Helpers';
    }

    protected function getNameInput(): string
    {
        return $this->argument('name') . 'Helper';
    }
}
