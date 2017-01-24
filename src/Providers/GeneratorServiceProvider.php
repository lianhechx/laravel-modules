<?php

namespace Opbol\Modules\Providers;

use Illuminate\Support\ServiceProvider;

class GeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $generators = [
            'command.make.module'            => \Opbol\Modules\Console\Generators\MakeModuleCommand::class,
            'command.make.module.controller' => \Opbol\Modules\Console\Generators\MakeControllerCommand::class,
            'command.make.module.middleware' => \Opbol\Modules\Console\Generators\MakeMiddlewareCommand::class,
            'command.make.module.migration'  => \Opbol\Modules\Console\Generators\MakeMigrationCommand::class,
            'command.make.module.model'      => \Opbol\Modules\Console\Generators\MakeModelCommand::class,
            'command.make.module.policy'     => \Opbol\Modules\Console\Generators\MakePolicyCommand::class,
            'command.make.module.provider'   => \Opbol\Modules\Console\Generators\MakeProviderCommand::class,
            'command.make.module.request'    => \Opbol\Modules\Console\Generators\MakeRequestCommand::class,
            'command.make.module.seeder'     => \Opbol\Modules\Console\Generators\MakeSeederCommand::class,
        ];

        foreach ($generators as $slug => $class) {
            $this->app->singleton($slug, function ($app) use ($slug, $class) {
                return $app[$class];
            });

            $this->commands($slug);
        }
    }
}
