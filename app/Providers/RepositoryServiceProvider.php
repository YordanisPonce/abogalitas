<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //

		$this->app->bind(
			\App\Interfaces\EloquentUserRepositoryInterface::class,
			\App\Repositories\EloquentUserRepository::class
		);

		$this->app->bind(
			\App\Interfaces\EloquentUserRepositoryInterface::class,
			\App\Repositories\EloquentUserRepository::class
		);

		$this->app->bind(
			\App\Interfaces\EloquentDocumentRepositoryInterface::class,
			\App\Repositories\EloquentDocumentRepository::class
		);

		$this->app->bind(
			\App\Interfaces\EloquentCategoryRepositoryInterface::class,
			\App\Repositories\EloquentCategoryRepository::class
		);

		$this->app->bind(
			\App\Interfaces\EloquentPlanRepositoryInterface::class,
			\App\Repositories\EloquentPlanRepository::class
		);

		$this->app->bind(
			\App\Interfaces\EloquentRoleRepositoryInterface::class,
			\App\Repositories\EloquentRoleRepository::class
		);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
