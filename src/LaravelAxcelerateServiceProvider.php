<?php

namespace malpaso\LaravelAxcelerate;

use malpaso\LaravelAxcelerate\Commands\LaravelAxcelerateCommand;
use malpaso\LaravelAxcelerate\Http\Client;
use malpaso\LaravelAxcelerate\Services\Contracts\CourseServiceInterface;
use malpaso\LaravelAxcelerate\Services\Contracts\CoursesServiceInterface;
use malpaso\LaravelAxcelerate\Services\CourseService;
use malpaso\LaravelAxcelerate\Services\CoursesService;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelAxcelerateServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-axcelerate')
            ->hasConfigFile('axcelerate')
            ->hasViews()
            ->hasMigration('create_axcelerate_table')
            ->hasCommand(LaravelAxcelerateCommand::class);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(Client::class, function ($app) {
            $config = $app['config']['axcelerate'];

            return new Client($config);
        });

        // Bind course services
        $this->app->singleton(CoursesServiceInterface::class, function ($app) {
            return new CoursesService($app[Client::class]);
        });

        $this->app->singleton(CourseServiceInterface::class, function ($app) {
            return new CourseService($app[Client::class]);
        });

        // Also bind concrete classes for direct injection
        $this->app->singleton(CoursesService::class, function ($app) {
            return $app[CoursesServiceInterface::class];
        });

        $this->app->singleton(CourseService::class, function ($app) {
            return $app[CourseServiceInterface::class];
        });

        $this->app->singleton(LaravelAxcelerate::class, function ($app) {
            return new LaravelAxcelerate(
                $app[Client::class],
                $app[CoursesServiceInterface::class],
                $app[CourseServiceInterface::class]
            );
        });
    }

    public function boot(): void
    {

        // Explicitly publish config file
        if ($this->app->runningInConsole()) {
            $configPath = __DIR__ . '/../config/axcelerate.php';
            $this->publishes([
                $configPath => config_path('axcelerate.php'),
            ], 'laravel-axcelerate-config');
        }
    }
}
