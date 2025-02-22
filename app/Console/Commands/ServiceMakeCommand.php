<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Artisan;
use App\Traits\ServiceProviderInjector;

class ServiceMakeCommand extends GeneratorCommand
{
    use ServiceProviderInjector;

    protected $signature = 'make:service {name}';
    protected $description = 'Create a new Service class';

    protected function getStub()
    {
        return realpath(".") . '/stubs/service.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\Services';
    }
}