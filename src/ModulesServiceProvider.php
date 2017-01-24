<?php

namespace Opbol\Modules;

use Opbol\Modules\Contracts\Repository;
use Opbol\Modules\Providers\BladeServiceProvider;
use Opbol\Modules\Providers\ConsoleServiceProvider;
use Opbol\Modules\Providers\GeneratorServiceProvider;
use Opbol\Modules\Providers\HelperServiceProvider;
use Opbol\Modules\Providers\RepositoryServiceProvider;
use Illuminate\Support\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    /**
     * @var bool Indicates if loading of the provider is deferred.
     */
    protected $defer = false;

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/modules.php' => config_path('modules.php'),
        ], 'config');

        $this->app['modules']->register();
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/modules.php', 'modules'
        );

        $this->app->register(ConsoleServiceProvider::class);
        $this->app->register(GeneratorServiceProvider::class);
        $this->app->register(HelperServiceProvider::class);
        $this->app->register(RepositoryServiceProvider::class);
        $this->app->register(BladeServiceProvider::class);

        $this->app->singleton('modules', function ($app) {
            $repository = $app->make(Repository::class);

            return new Modules($app, $repository);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string
     */
    public function provides()
    {
        return ['modules'];
    }

    public static function compiles()
    {
        $modules = app()->make('modules')->all();
        $files = [];

        foreach ($modules as $module) {
            $serviceProvider = module_class($module['slug'], 'Providers\\ModuleServiceProvider');

            if (class_exists($serviceProvider)) {
                $files = array_merge($files, forward_static_call([$serviceProvider, 'compiles']));
            }
        }

        return array_map('realpath', $files);
    }
}
