<?php

namespace App\Traits;

use ReflectionClass;

trait ServiceProviderInjector
{
    public function injectCodeToRegisterMethod($repositoryServiceProviderFile, $codeToAdd)
    {
        $reflectionClass = new ReflectionClass(\App\Providers\RepositoryServiceProvider::class);
        $reflectionMethod = $reflectionClass->getMethod('register');

        $methodBody = file($repositoryServiceProviderFile);

        $startLine = $reflectionMethod->getStartLine() - 1;
        $endLine = $reflectionMethod->getEndLine() - 1;

        array_splice($methodBody, $endLine, 0, $codeToAdd);
        $modifiedCode = implode('', $methodBody);

        file_put_contents($repositoryServiceProviderFile, $modifiedCode);
    }
}
